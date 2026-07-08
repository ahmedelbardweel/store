document.addEventListener('DOMContentLoaded', function() {
    const settingsToggle = document.getElementById('settings-toggle');
    const settingsClose = document.getElementById('settings-close');
    const settingsDrawer = document.getElementById('settings-drawer');
    const settingsBackdrop = document.getElementById('settings-backdrop');
    const isRtl = (document.querySelector('meta[name="locale"]')?.content || document.documentElement.lang) === 'ar';

    function openSettings() {
        settingsBackdrop.classList.remove('pointer-events-none', 'opacity-0');
        settingsBackdrop.classList.add('opacity-100');
        settingsDrawer.classList.remove(isRtl ? '-translate-x-full' : 'translate-x-full');
        settingsDrawer.classList.add('translate-x-0');
    }

    function closeSettings() {
        settingsBackdrop.classList.remove('opacity-100');
        settingsBackdrop.classList.add('opacity-0', 'pointer-events-none');
        settingsDrawer.classList.remove('translate-x-0');
        settingsDrawer.classList.add(isRtl ? '-translate-x-full' : 'translate-x-full');
    }

    if (settingsToggle) settingsToggle.addEventListener('click', openSettings);
    if (settingsClose) settingsClose.addEventListener('click', closeSettings);
    if (settingsBackdrop) settingsBackdrop.addEventListener('click', closeSettings);

    const themeLightBtn = document.getElementById('theme-light');
    const themeDarkBtn = document.getElementById('theme-dark');

    function updateThemeButtons() {
        const isDark = document.documentElement.classList.contains('dark');
        if (isDark) {
            themeDarkBtn.classList.add('border-[#f53003]', 'text-[#f53003]', 'bg-red-50/50', 'dark:bg-red-950/10');
            themeLightBtn.classList.remove('border-[#f53003]', 'text-[#f53003]', 'bg-red-50/50', 'dark:bg-red-950/10');
        } else {
            themeLightBtn.classList.add('border-[#f53003]', 'text-[#f53003]', 'bg-red-50/50', 'dark:bg-red-950/10');
            themeDarkBtn.classList.remove('border-[#f53003]', 'text-[#f53003]', 'bg-red-50/50', 'dark:bg-red-950/10');
        }
    }

    themeLightBtn.addEventListener('click', () => {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        updateThemeButtons();
    });

    themeDarkBtn.addEventListener('click', () => {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        updateThemeButtons();
    });

    updateThemeButtons();

    const deleteAccountBtn = document.getElementById('delete-account-btn');
    if (deleteAccountBtn) {
        deleteAccountBtn.addEventListener('click', function() {
            const confirmationText = isRtl
                ? "هل أنت متأكد تمامًا من رغبتك في حذف حسابك بشكل نهائي؟ لا يمكن التراجع عن هذا الإجراء!"
                : "Are you absolutely sure you want to delete your account? This action cannot be undone!";
            if (confirm(confirmationText)) {
                document.getElementById('delete-account-form').submit();
            }
        });
    }

    function updateCartBadge() {
        var url = document.querySelector('meta[name="cart-count-url"]')?.content;
        fetch(url || '/cart/count').then(r => r.json())
            .then(data => {
                const badge = document.getElementById('cart-badge');
                if (data.count > 0) {
                    badge.innerText = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
    }
    updateCartBadge();

    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            hamburgerIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
            document.getElementById('mobile-search-bar').classList.add('hidden');
        });
    }

    const mobileSearchToggle = document.getElementById('mobile-search-toggle');
    const mobileSearchBar = document.getElementById('mobile-search-bar');
    if (mobileSearchToggle) {
        mobileSearchToggle.addEventListener('click', function() {
            mobileSearchBar.classList.toggle('hidden');
            mobileMenu.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            if (!mobileSearchBar.classList.contains('hidden')) {
                document.getElementById('mobile-search-input').focus();
            }
        });
    }

    function setupSearch(inputId, resultsId, spinnerId) {
        const input = document.getElementById(inputId);
        const results = document.getElementById(resultsId);
        const spinner = spinnerId ? document.getElementById(spinnerId) : null;
        if (!input || !results) return;

        let timeout = null;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value.trim();
            if (query.length < 2) {
                results.innerHTML = '';
                results.classList.add('hidden');
                return;
            }
            if (spinner) spinner.classList.remove('hidden');
            timeout = setTimeout(() => {
                fetch(`/search?q=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(data => {
                        if (spinner) spinner.classList.add('hidden');
                        if (data.length === 0) {
                            results.innerHTML = '<div class="p-4 text-xs text-gray-500 text-center">No products found.</div>';
                        } else {
                            results.innerHTML = data.map(item => `
                                <a href="${item.url}" class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-zinc-900 border-b border-gray-100 dark:border-zinc-800/50 last:border-none transition-colors">
                                    <img src="${item.thumbnail}" width="36" height="36" class="w-9 h-9 object-cover rounded-lg border border-gray-100 dark:border-zinc-800 shrink-0" />
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-semibold truncate text-gray-800 dark:text-zinc-200">${item.name}</div>
                                        <div class="text-[10px] text-gray-400">${item.category}</div>
                                    </div>
                                    <div class="text-xs font-bold text-[#f53003] shrink-0">${item.price}</div>
                                </a>
                            `).join('');
                        }
                        results.classList.remove('hidden');
                    })
                    .catch(() => { if (spinner) spinner.classList.add('hidden'); });
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.classList.add('hidden');
            }
        });
    }

    setupSearch('global-search', 'search-results', 'search-spinner');
    setupSearch('mobile-search-input', 'mobile-search-results', 'mobile-search-spinner');
});