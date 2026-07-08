@extends('layouts.store')

@section('title', 'Secure Checkout - Store13')

@section('content')
    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-gray-900 dark:text-white mb-5 sm:mb-6">Secure Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Left 2 Columns: Payment Information Form -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 🧪 Test Card Helper Box -->
            <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-300 dark:border-amber-700 rounded-[15px] p-4 sm:p-5 space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.214.155-.385.342-.385h.39c.187 0 .342-.171.342-.385v-.706c0-.214-.155-.385-.342-.385H9.01c-.187 0-.342.171-.342.385v.706c0 .214.155.385.342.385h.39c.187 0 .342.171.342.385v7.234a9.075 9.075 0 0 1-1.637 5.17c-.456.634-.074 1.517.697 1.517h7.206c.77 0 1.153-.883.697-1.517a9.075 9.075 0 0 1-1.637-5.17V6.087Z" />
                        </svg>
                        <span class="font-bold text-xs text-amber-800 dark:text-amber-400 uppercase tracking-wider">Test Mode</span>
                    </div>
                    <button 
                        type="button" 
                        id="fill-test-card"
                        class="text-[10px] px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-[10px] font-bold transition-all cursor-pointer shrink-0 flex items-center gap-1"
                    >
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        Auto-Fill
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 text-[11px]">
                    <div class="bg-white dark:bg-zinc-900 rounded-[12px] p-2.5 sm:p-3 border border-amber-200 dark:border-amber-800">
                        <span class="block text-gray-400 mb-0.5">Name</span>
                        <span class="font-bold text-gray-800 dark:text-zinc-200 font-mono text-[11px]">Ahmed Test</span>
                    </div>
                    <div class="bg-white dark:bg-zinc-900 rounded-[12px] p-2.5 sm:p-3 border border-amber-200 dark:border-amber-800">
                        <span class="block text-gray-400 mb-0.5">Card No.</span>
                        <span class="font-bold text-gray-800 dark:text-zinc-200 font-mono text-[11px] break-all">4242-4242-4242-4242</span>
                    </div>
                    <div class="bg-white dark:bg-zinc-900 rounded-[12px] p-2.5 sm:p-3 border border-amber-200 dark:border-amber-800">
                        <span class="block text-gray-400 mb-0.5">Expiry</span>
                        <span class="font-bold text-gray-800 dark:text-zinc-200 font-mono">12/28</span>
                    </div>
                    <div class="bg-white dark:bg-zinc-900 rounded-[12px] p-2.5 sm:p-3 border border-amber-200 dark:border-amber-800">
                        <span class="block text-gray-400 mb-0.5">CVV</span>
                        <span class="font-bold text-gray-800 dark:text-zinc-200 font-mono">123</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-[15px] p-6 custom-shadow space-y-6">
                <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Payment Information</h2>
                
                <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Cardholder Name -->
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Cardholder Name</label>
                        <input 
                            type="text" 
                            id="card_name"
                            name="card_name" 
                            required 
                            placeholder="Ahmed Test" 
                            class="w-full px-3 py-2 rounded-[12px] border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-sm focus:outline-none focus:border-[#f53003] transition-colors"
                        >
                    </div>

                    <!-- Card Number -->
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Card Number</label>
                        <input 
                            type="text" 
                            id="card_number" 
                            name="card_number" 
                            required 
                            maxlength="19"
                            placeholder="4242-4242-4242-4242" 
                            class="w-full px-3 py-2 rounded-[12px] border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-sm focus:outline-none focus:border-[#f53003] transition-colors"
                        >
                    </div>

                    <!-- Expiry & CVV -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Expiration Date</label>
                            <input 
                                type="text" 
                                id="card_expiry"
                                name="card_expiry" 
                                required 
                                maxlength="5"
                                placeholder="12/28" 
                                class="w-full px-3 py-2 rounded-[12px] border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-sm focus:outline-none focus:border-[#f53003] transition-colors"
                            >
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">CVV</label>
                            <input 
                                type="text" 
                                id="card_cvv"
                                name="card_cvv" 
                                required 
                                maxlength="4"
                                placeholder="123" 
                                class="w-full px-3 py-2 rounded-[12px] border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-sm focus:outline-none focus:border-[#f53003] transition-colors"
                            >
                        </div>
                    </div>

                    <!-- Order button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full text-center text-xs py-3 bg-[#f53003] hover:bg-red-700 text-white rounded-[12px] font-bold transition-all cursor-pointer">
                            Pay &amp; Complete Order
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right 1 Column: Summary details -->
        <div class="space-y-6">
            
            <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-[15px] p-6 custom-shadow space-y-6">
                <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Your Order Items</h2>
                
                <div class="divide-y divide-gray-100 dark:divide-zinc-800/80 text-xs">
                    @foreach ($items as $item)
                        @php $p = $item['product']; @endphp
                        <div class="flex items-center justify-between py-3">
                            <div class="min-w-0 pr-4">
                                <span class="font-bold text-gray-800 dark:text-zinc-200 truncate block">{{ $p->name }}</span>
                                <span class="text-[10px] text-gray-400 block">{{ $p->category->name }}</span>
                            </div>
                            <span class="font-black text-gray-900 dark:text-white shrink-0">{{ $p->formatted_price }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 dark:border-zinc-800 pt-4 text-xs space-y-2">
                    <div class="flex justify-between text-gray-400">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if ($discount > 0)
                        <div class="flex justify-between text-emerald-600 dark:text-emerald-400">
                            <span>Discount ({{ $coupon }})</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm pt-2 border-t border-gray-100 dark:border-zinc-800/50">
                        <span class="font-bold text-gray-900 dark:text-white">Total</span>
                        <span class="font-black text-[#f53003] dark:text-[#FF4433]">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
<script>
    // Format Card Number (XXXX-XXXX-XXXX-XXXX)
    const cardInput = document.getElementById('card_number');
    if (cardInput) {
        cardInput.addEventListener('input', function (e) {
            let val = this.value.replace(/\D/g, '');
            let matches = val.match(/\d{4,16}/g);
            let match = matches && matches[0] || '';
            let parts = [];

            for (let i = 0, len = match.length; i < len; i += 4) {
                parts.push(match.substring(i, i + 4));
            }

            if (parts.length > 0) {
                this.value = parts.join('-');
            } else {
                this.value = val;
            }
        });
    }

    // Format Card Expiry (MM/YY)
    const expiryInput = document.getElementById('card_expiry');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let val = this.value.replace(/\D/g, '');
            if (val.length >= 2) {
                this.value = val.substring(0, 2) + '/' + val.substring(2, 4);
            } else {
                this.value = val;
            }
        });
    }

    // ✦ Auto-Fill Test Card Button
    const fillBtn = document.getElementById('fill-test-card');
    if (fillBtn) {
        fillBtn.addEventListener('click', function () {
            document.getElementById('card_name').value  = 'Ahmed Test';
            document.getElementById('card_number').value = '4242-4242-4242-4242';
            document.getElementById('card_expiry').value = '12/28';
            document.getElementById('card_cvv').value    = '123';

            // Flash green border briefly on all inputs
            ['card_name','card_number','card_expiry','card_cvv'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.add('border-emerald-400');
                    setTimeout(() => el.classList.remove('border-emerald-400'), 1500);
                }
            });

            // Update button text temporarily
            this.textContent = '✓ Filled!';
            this.classList.replace('bg-amber-500', 'bg-emerald-500');
            setTimeout(() => {
                this.textContent = '✦ Auto-Fill Test Card';
                this.classList.replace('bg-emerald-500', 'bg-amber-500');
            }, 2000);
        });
    }
</script>
@endsection



