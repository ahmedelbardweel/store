<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Switch language locale.
     */
    public function setLanguage(Request $request)
    {
        $locale = $request->input('locale');
        if (in_array($locale, ['ar', 'en'])) {
            session()->put('locale', $locale);
        }
        return back()->with('success', $locale === 'ar' ? 'تم تغيير اللغة إلى العربية' : 'Language changed to English');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('Password change failed. Current password is incorrect.')]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', __('Password changed successfully!'));
    }

    /**
     * Delete user account.
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        // Remove from saved accounts
        $savedAccounts = json_decode($request->cookie('saved_accounts', '{}'), true);
        if (!is_array($savedAccounts)) {
            $savedAccounts = [];
        }
        if (isset($savedAccounts[$user->id])) {
            unset($savedAccounts[$user->id]);
            cookie()->queue('saved_accounts', json_encode($savedAccounts), 60 * 24 * 30);
        }

        // Delete user
        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', __('Your account has been deleted.'));
    }

    /**
     * Switch to a different saved account.
     */
    public function switchAccount(Request $request, $id)
    {
        $savedAccounts = json_decode($request->cookie('saved_accounts', '{}'), true);
        if (!is_array($savedAccounts)) {
            $savedAccounts = [];
        }

        if (!isset($savedAccounts[$id])) {
            return back()->with('error', __('Account not found. Please log in first.'));
        }

        $user = User::find($id);
        if (!$user) {
            return back()->with('error', __('Account no longer exists.'));
        }

        // Log out current
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log in the selected one
        Auth::login($user);

        // Keep the saved accounts updated
        $savedAccounts[$user->id] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
        cookie()->queue('saved_accounts', json_encode($savedAccounts), 60 * 24 * 30);

        return redirect()->route('home')->with('success', __('Switched account successfully!'));
    }

    /**
     * Remove a saved account from the switcher list (without deleting it).
     */
    public function removeSavedAccount(Request $request, $id)
    {
        $savedAccounts = json_decode($request->cookie('saved_accounts', '{}'), true);
        if (!is_array($savedAccounts)) {
            $savedAccounts = [];
        }
        if (isset($savedAccounts[$id])) {
            unset($savedAccounts[$id]);
            cookie()->queue('saved_accounts', json_encode($savedAccounts), 60 * 24 * 30);
        }
        return back()->with('success', __('Account removed from switcher.'));
    }
}
