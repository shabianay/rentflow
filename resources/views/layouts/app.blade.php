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

<body class="flex min-h-screen flex-col bg-slate-50 dark:bg-slate-900 transition-colors">
    @guest
        {{-- Guest Layout --}}
        <div class="flex w-full flex-1 flex-col">
        <header class="border-b border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-800">
            <nav class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.webp') }}" alt="RentFlow" class="h-8 w-auto">
                </a>
                {{-- Desktop nav --}}
                <div class="hidden items-center gap-4 lg:flex">
                    <a href="{{ route('catalog') }}"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Katalog</a>
                    <a href="{{ route('login') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                    <button class="js-dark-toggle rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                        title="Toggle Dark Mode">
                        <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-2 lg:hidden">
                    <button class="js-dark-toggle rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                        title="Toggle Dark Mode">
                        <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                    <button id="nav-toggle"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700"
                        aria-label="Buka menu navigasi">
                        <svg id="nav-icon-open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="nav-icon-close" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </nav>
            {{-- Mobile nav menu --}}
            <div id="nav-menu" class="hidden border-t border-slate-100 px-4 pb-4 pt-3 dark:border-slate-700 lg:hidden">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('catalog') }}"
                        class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-indigo-600 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-indigo-400">Katalog</a>
                    <a href="{{ route('login') }}"
                        class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary justify-center">Daftar</a>
                </div>
            </div>
        </header>

        <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('partials.session-messages')
        </div>

        <main class="flex-1 mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </main>
        <footer class="mt-16 border-t border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-800">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Platform rental terpercaya untuk memenuhi kebutuhan Anda. Booking, bayar, dan nikmati dengan mudah.</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Links</h4>
                        <ul class="mt-4 space-y-2">
                            <li><a href="{{ route('catalog') }}" class="text-sm text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Katalog</a></li>
                            <li><a href="{{ route('privacy') }}" class="text-sm text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Kebijakan Privasi</a></li>
                            <li><a href="{{ route('terms') }}" class="text-sm text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kontak</h4>
                        <ul class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                            <li class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                support@rentflow.id
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                +62 812-3456-7890
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-slate-100 pt-6 text-center text-xs text-slate-400 dark:border-slate-700 dark:text-slate-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </div>
        </footer>
        </div>
    @elseif (Auth::user()->role === 'admin')
        {{-- Admin Layout --}}
        <div class="flex h-screen overflow-hidden">
            <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-900/40 lg:hidden dark:bg-slate-900/60"></div>
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-100 bg-white transition-transform -translate-x-full lg:translate-x-0 lg:static dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-16 items-center gap-2 border-b border-slate-100 px-6 dark:border-slate-700">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg">
                        <img src="{{ asset('images/logo.webp') }}" alt="RentFlow" class="h-8 w-8 rounded-lg object-cover">
                    </div>
                    <button id="sidebar-close" class="ml-auto rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 lg:hidden" type="button">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <div class="pt-4">
                        <div class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Manajemen</div>
                        <a href="{{ route('admin.units.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.units.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Unit
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Kategori
                        </a>
                        <a href="{{ route('admin.customers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.customers') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Customer
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Booking
                        </a>
                        <a href="{{ route('admin.calendar') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.calendar') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Kalender
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Pembayaran
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.invoices.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Invoice
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Laporan
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Notifikasi
                        </a>
                    </div>
                </nav>
                <div class="border-t border-slate-100 p-5 dark:border-slate-700">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('profile.edit') }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-base font-bold text-white hover:opacity-90 shadow-sm" title="Edit Profil">{{ substr(Auth::user()->name, 0, 1) }}</a>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('profile.edit') }}" class="block truncate text-sm font-medium text-slate-800 hover:text-indigo-600 dark:text-slate-200 dark:hover:text-indigo-400">{{ Auth::user()->name }}</a>
                            <div class="text-xs text-slate-500 capitalize dark:text-slate-400">{{ Auth::user()->role }}</div>
                        </div>
                        <button id="dark-toggle-admin" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700" title="Toggle Dark Mode">
                            <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </button>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700" title="Logout">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex flex-1 flex-col overflow-hidden">
                <header class="flex h-16 items-center gap-4 border-b border-slate-100 bg-white px-4 lg:px-8 dark:border-slate-700 dark:bg-slate-800">
                    <button id="sidebar-toggle" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:text-slate-400 dark:hover:bg-slate-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex-1">
                        <h1 class="text-lg font-semibold text-slate-800 dark:text-slate-100">@yield('page_title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">@yield('page_subtitle', 'Overview bisnis rental Anda')</p>
                    </div>
                </header>

                @include('partials.session-messages')

                <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        {{-- Customer Layout --}}
        <div class="flex w-full flex-1 flex-col">
        <header class="border-b border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-800">
            <nav class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.webp') }}" alt="RentFlow" class="h-8 w-auto">
                </a>
                <div class="hidden items-center gap-4 lg:flex">
                    <a href="{{ route('catalog') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Katalog</a>
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Dashboard</a>
                    <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Booking Saya</a>
                    <a href="{{ route('invoices.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Invoice</a>
                    <a href="{{ route('profile.edit') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">Profil</a>
                    <form method="post" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/40">Logout</button>
                    </form>
                    @php $cartCount = count(session('cart', [])); @endphp
                    <a href="{{ route('cart.index') }}"
                        class="relative rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                        title="Keranjang">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        @if ($cartCount > 0)
                            <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-bold text-white ring-2 ring-white dark:ring-slate-800">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <button class="js-dark-toggle rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                        title="Toggle Dark Mode">
                        <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-2 lg:hidden">
                    <button class="js-dark-toggle rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                        title="Toggle Dark Mode">
                        <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                    @php $cartCountM = count(session('cart', [])); @endphp
                    <a href="{{ route('cart.index') }}"
                        class="relative rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                        title="Keranjang">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        @if ($cartCountM > 0)
                            <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-bold text-white ring-2 ring-white dark:ring-slate-800">{{ $cartCountM }}</span>
                        @endif
                    </a>
                    <button id="nav-toggle"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700"
                        aria-label="Buka menu navigasi">
                        <svg id="nav-icon-open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="nav-icon-close" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </nav>
            <div id="nav-menu" class="hidden border-t border-slate-100 px-4 pb-4 pt-3 dark:border-slate-700 lg:hidden">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('catalog') }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-indigo-600 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-indigo-400">Katalog</a>
                    <a href="{{ route('cart.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700">
                        Keranjang
                        @php $cartCountMobile = count(session('cart', [])); @endphp
                        @if ($cartCountMobile > 0)
                            <span class="flex h-5 min-w-[20px] items-center justify-center rounded-full bg-indigo-600 px-1.5 text-[10px] font-bold text-white">{{ $cartCountMobile }}</span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700">Dashboard</a>
                    <a href="{{ route('bookings.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700">Booking Saya</a>
                    <a href="{{ route('invoices.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700">Invoice</a>
                    <a href="{{ route('profile.edit') }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700">Profil</a>
                    <form method="post" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button class="w-full rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/40 text-left">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('partials.session-messages')
        </div>

        <main class="flex-1 mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </main>
        <footer class="mt-16 border-t border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-800">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Platform rental terpercaya untuk memenuhi kebutuhan Anda. Booking, bayar, dan nikmati dengan mudah.</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Links</h4>
                        <ul class="mt-4 space-y-2">
                            <li><a href="{{ route('catalog') }}" class="text-sm text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Katalog</a></li>
                            <li><a href="{{ route('privacy') }}" class="text-sm text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Kebijakan Privasi</a></li>
                            <li><a href="{{ route('terms') }}" class="text-sm text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kontak</h4>
                        <ul class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                            <li class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                support@rentflow.id
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                +62 812-3456-7890
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-slate-100 pt-6 text-center text-xs text-slate-400 dark:border-slate-700 dark:text-slate-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </div>
        </footer>
        </div>
    @endguest

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
            document.querySelectorAll('.js-dark-toggle, #dark-toggle-admin').forEach(function(btn) {
                if (btn) btn.addEventListener('click', toggleDark);
            });
        })();

        // Mobile Nav Toggle
        (function() {
            const toggle = document.getElementById('nav-toggle');
            const menu = document.getElementById('nav-menu');
            const iconOpen = document.getElementById('nav-icon-open');
            const iconClose = document.getElementById('nav-icon-close');
            if (toggle && menu) {
                toggle.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                    iconOpen?.classList.toggle('hidden');
                    iconClose?.classList.toggle('hidden');
                    toggle.setAttribute('aria-label', menu.classList.contains('hidden') ? 'Buka menu navigasi' : 'Tutup menu navigasi');
                });
            }
        })();

        // Confirm dialog for data-confirm elements (customer)
        (function() {
            document.querySelectorAll('[data-confirm]').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    var msg = this.getAttribute('data-confirm') || 'Apakah Anda yakin?';
                    if (!confirm(msg)) e.preventDefault();
                });
            });
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
    @stack('scripts')

    {{-- Lightbox --}}
    <div id="lightbox" onclick="this.classList.add('hidden')" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-black/80 p-4">
        <button onclick="event.stopPropagation();closeLightbox()" class="absolute right-4 top-4 z-10 text-3xl text-white/70 hover:text-white">&times;</button>
        <button id="lightbox-prev" onclick="event.stopPropagation();galleryNav(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 text-4xl text-white/70 hover:text-white">&lsaquo;</button>
        <img id="lightbox-img" class="max-h-[90vh] max-w-full rounded-lg object-contain shadow-2xl m-auto" onclick="event.stopPropagation()">
        <button id="lightbox-next" onclick="event.stopPropagation();galleryNav(1)" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 text-4xl text-white/70 hover:text-white">&rsaquo;</button>
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

        function updateGalleryButtons() {
            var prev = document.getElementById('lightbox-prev');
            var next = document.getElementById('lightbox-next');
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
