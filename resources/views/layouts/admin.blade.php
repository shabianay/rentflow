<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @stack('styles')
</head>

<body class="min-h-screen bg-slate-50 dark:bg-slate-900 transition-colors">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-900/40 lg:hidden dark:bg-slate-900/60">
        </div>
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-100 bg-white transition-transform -translate-x-full lg:translate-x-0 lg:static dark:border-slate-700 dark:bg-slate-800">
            <div class="flex h-16 items-center gap-2 border-b border-slate-100 px-6 dark:border-slate-700">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg ">
                    <img src="{{ asset('images/logo.webp') }}" alt="RentFlow" class="h-8 w-8 rounded-lg object-cover">
                </div>
                <button id="sidebar-close"
                    class="ml-auto rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 lg:hidden"
                    type="button">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <div class="pt-4">
                    <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Manajemen</div>
                    <a href="{{ route('admin.units.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.units.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Unit
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Kategori
                    </a>
                    <a href="{{ route('admin.customers') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.customers') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Customer
                    </a>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.bookings.*') && !request()->routeIs('admin.bookings.create') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Booking
                    </a>

                    <a href="{{ route('admin.payments.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Pembayaran
                    </a>
                    <a href="{{ route('admin.invoices.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.invoices.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Invoice
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Laporan
                    </a>
                    <a href="{{ route('admin.reviews.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Review
                    </a>
                </div>
            </nav>
            <div class="border-t border-slate-100 p-5 dark:border-slate-700">
                <div class="flex items-center gap-4">
                    <a href="{{ route('profile.edit') }}"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-base font-bold text-white hover:opacity-90 shadow-sm"
                        title="Edit Profil">{{ substr(Auth::user()->name, 0, 1) }}</a>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('profile.edit') }}"
                            class="block truncate text-sm font-medium text-slate-800 hover:text-indigo-600 dark:text-slate-200 dark:hover:text-indigo-400">{{ Auth::user()->name }}</a>
                        <div class="text-xs text-slate-500 capitalize dark:text-slate-400">{{ Auth::user()->role }}
                        </div>
                    </div>
                    <button id="dark-toggle-admin"
                        class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                        title="Toggle Dark Mode">
                        <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                            title="Logout">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex flex-1 flex-col overflow-hidden">
            <header
                class="flex h-16 items-center gap-4 border-b border-slate-100 bg-white px-4 lg:px-8 dark:border-slate-700 dark:bg-slate-800">
                <button id="sidebar-toggle"
                    class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:text-slate-400 dark:hover:bg-slate-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1">
                    <h1 class="text-lg font-semibold text-slate-800 dark:text-slate-100">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">@yield('page_subtitle', 'Overview bisnis rental Anda')</p>
                </div>
                <div class="relative shrink-0" id="notif-wrapper">
                    <button id="notif-bell" class="relative">
                        <svg class="h-6 w-6 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span id="notif-badge" class="absolute -top-1.5 -right-1.5 hidden inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold leading-none text-white ring-2 ring-white dark:ring-slate-800">0</span>
                    </button>
                    <div id="notif-dropdown" class="absolute right-0 top-full mt-2 hidden w-80 rounded-xl border border-slate-200 bg-white shadow-lg dark:border-slate-600 dark:bg-slate-800 z-50">
                        <div class="p-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">Notifikasi</span>
                            <form method="post" action="{{ route('admin.notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Tandai Dibaca</button>
                            </form>
                        </div>
                        <div id="notif-list" class="max-h-72 overflow-y-auto">
                            <div class="p-4 text-center text-sm text-slate-400 dark:text-slate-500">Memuat...</div>
                        </div>
                        <a href="{{ route('admin.notifications.index') }}" class="block p-3 text-center text-sm font-medium text-indigo-600 hover:text-indigo-700 border-t border-slate-100 dark:border-slate-700 dark:text-indigo-400 dark:hover:text-indigo-300">Lihat Semua</a>
                    </div>
                </div>
            </header>

            <div class="mx-4 mt-4 lg:mx-8">
                @include('partials.session-messages')
            </div>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggle = document.getElementById('sidebar-toggle');
            const closeBtn = document.getElementById('sidebar-close');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
                document.body.style.overflow = '';
            }

            toggle?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);
        })();

        // Dark Mode Toggle
        (function() {
            function toggleDark() {
                const html = document.documentElement;
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('darkMode', isDark);
            }
            const btn = document.getElementById('dark-toggle-admin');
            if (btn) btn.addEventListener('click', toggleDark);
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
    @stack('scripts')

    <script>
    (function() {
        var notifOpen = false;

        function renderNotifList(notifs) {
            var list = document.getElementById('notif-list');
            if (!list) return;
            if (!notifs || notifs.length === 0) {
                list.innerHTML = '<div class="p-4 text-center text-sm text-slate-400 dark:text-slate-500">Tidak ada notifikasi.</div>';
                return;
            }
            list.innerHTML = notifs.map(function(n) {
                var isRead = n.is_read ? 'opacity-60' : '';
                var dot = n.is_read ? '' : '<span class="h-2 w-2 rounded-full bg-indigo-500 shrink-0"></span>';
                var url = n.url || '#';
                return '<div class="flex items-start gap-3 px-3 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 ' + isRead + ' border-b border-slate-50 dark:border-slate-700/50">' +
                    '<div class="flex-1 min-w-0">' +
                    '<a href="' + url + '" class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate block">' + n.title + '</a>' +
                    (n.message ? '<p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">' + n.message + '</p>' : '') +
                    '<p class="text-[10px] text-slate-400 mt-1">' + timeAgo(n.created_at) + '</p>' +
                    '</div>' +
                    (n.is_read ? '' : '<button onclick="markNotifRead(' + n.id + ', this)" class="shrink-0 text-[10px] text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 px-1">Tutup</button>') +
                    '</div>';
            }).join('');
        }

        function timeAgo(dateStr) {
            var d = new Date(dateStr);
            var now = new Date();
            var diff = Math.floor((now - d) / 1000);
            if (diff < 60) return 'baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + 'm';
            if (diff < 86400) return Math.floor(diff / 3600) + 'j';
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        }

        function updateNotifBadge() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(r => r.json())
                .then(function(data) {
                    var badge = document.getElementById('notif-badge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.classList.remove('hidden');
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                })
                .catch(function() {});
            if (notifOpen) fetchNotifList();
        }

        function fetchNotifList() {
            fetch('{{ route("notifications.latest") }}')
                .then(function(r) { return r.json(); })
                .then(function(data) { renderNotifList(data); })
                .catch(function() {});
        }

        document.addEventListener('click', function(e) {
            var wrapper = document.getElementById('notif-wrapper');
            var dropdown = document.getElementById('notif-dropdown');
            if (!wrapper || !dropdown) return;
            if (wrapper.contains(e.target)) {
                if (e.target.closest('#notif-bell') || e.target.closest('#notif-badge')) {
                    e.preventDefault();
                    notifOpen = !notifOpen;
                    if (notifOpen) {
                        dropdown.classList.remove('hidden');
                        fetchNotifList();
                    } else {
                        dropdown.classList.add('hidden');
                    }
                }
            } else {
                notifOpen = false;
                dropdown.classList.add('hidden');
            }
        });

        updateNotifBadge();
        setInterval(updateNotifBadge, 30000);
    })();

    function markNotifRead(id, btn) {
        fetch('/admin/notifications/' + id + '/read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        }).then(function(r) { return r.json(); }).then(function(d) {
            if (d.status === 'ok') {
                var item = btn.closest('.flex');
                if (item) {
                    item.classList.add('opacity-60');
                    btn.remove();
                    var dot = item.querySelector('.rounded-full.bg-indigo-500');
                    if (dot) dot.remove();
                }
                var badge = document.getElementById('notif-badge');
                var count = badge ? parseInt(badge.textContent) : 0;
                if (badge && count > 1) {
                    badge.textContent = count - 1;
                } else if (badge) {
                    badge.classList.add('hidden');
                }
            }
        }).catch(function() {});
    }
    </script>

    {{-- Lightbox --}}
    <div id="lightbox" onclick="closeLightbox()"
        class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-black/80 p-4">
        <button onclick="event.stopPropagation();closeLightbox()"
            class="absolute right-4 top-4 z-10 text-3xl text-white/70 hover:text-white">&times;</button>
        <button onclick="event.stopPropagation();galleryNav(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 text-4xl text-white/70 hover:text-white">&lsaquo;</button>
        <img id="lightbox-img" class="max-h-[90vh] max-w-full rounded-lg object-contain shadow-2xl m-auto"
            onclick="event.stopPropagation()">
        <button onclick="event.stopPropagation();galleryNav(1)" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 text-4xl text-white/70 hover:text-white">&rsaquo;</button>
    </div>
    <script>
        var galleryPhotos = [];
        var galleryIndex = 0;

        function openLightbox(src, photos) {
            if (photos && photos.length > 0) {
                galleryPhotos = photos;
                galleryIndex = photos.indexOf(src);
                if (galleryIndex === -1) galleryIndex = 0;
            } else {
                galleryPhotos = [src];
                galleryIndex = 0;
            }
            document.getElementById('lightbox-img').src = galleryPhotos[galleryIndex];
            document.getElementById('lightbox').classList.remove('hidden');
            updateGalleryButtons();
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
        }

        function galleryNav(dir) {
            galleryIndex += dir;
            if (galleryIndex < 0) galleryIndex = galleryPhotos.length - 1;
            if (galleryIndex >= galleryPhotos.length) galleryIndex = 0;
            document.getElementById('lightbox-img').src = galleryPhotos[galleryIndex];
            updateGalleryButtons();
        }

        function confirmAction(e) {
            var msg = this.getAttribute('data-confirm') || 'Apakah Anda yakin?';
            if (!confirm(msg)) e.preventDefault();
        }
        document.querySelectorAll('[data-confirm]').forEach(function(el) {
            el.addEventListener('click', confirmAction);
        });

        function updateGalleryButtons() {
            var prev = document.getElementById('lightbox').querySelector('.left-4');
            var next = document.getElementById('lightbox').querySelector('.right-4');
            if (!prev || !next) return;
            if (galleryPhotos.length <= 1) {
                prev.classList.add('hidden');
                next.classList.add('hidden');
            } else {
                prev.classList.remove('hidden');
                next.classList.remove('hidden');
            }
        }
    </script>
</body>

</html>
