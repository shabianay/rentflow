@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="flex min-h-[calc(100vh-8rem)] items-center justify-center">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl  text-2xl font-bold text-white shadow-lg shadow-indigo-200">
                    <img src="{{ asset('images/logo.webp') }}" alt="RentFlow" class="h-8 w-8 rounded-lg object-cover">
                </div>
                <h1 class="mt-4 text-2xl font-bold text-slate-800 dark:text-slate-100">Selamat Datang Kembali</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Masuk ke akun RentFlow Anda</p>
            </div>
            <div class="card">
                <form method="post" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 dark:text-slate-300">Email</label>
                        <input type="email" name="email" id="email"
                             class="mt-1.5 input-field @error('email') border-red-300 dark:border-red-500 @enderror" placeholder="nama@email.com"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 dark:text-slate-300">Password</label>
                        <input type="password" name="password" id="password"
                             class="mt-1.5 input-field @error('email') border-red-300 dark:border-red-500 @enderror"
                            placeholder="Masukkan password" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" name="remember"
                                 class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:text-indigo-400 dark:focus:ring-indigo-400">
                            Ingat saya
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Lupa
                            password?</a>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Masuk
                    </button>
                </form>
                <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Daftar
                        sekarang</a>
                </div>
            </div>
        </div>
    </div>
@endsection
