@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
    <div class="flex min-h-[calc(100vh-8rem)] items-center justify-center">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl text-2xl font-bold text-white shadow-lg shadow-indigo-200">
                    <img src="{{ asset('images/logo.webp') }}" alt="RentFlow" class="h-8 w-8 rounded-lg object-cover">
                </div>
                <h1 class="mt-4 text-2xl font-bold text-slate-800 dark:text-slate-100">Lupa Password</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Masukkan email Anda untuk menerima tautan reset password</p>
            </div>
            <div class="card">
                <form method="post" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-1.5 input-field @error('email') border-red-300 dark:border-red-500 @enderror"
                            placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <svg class="mr-2 h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Kirim Tautan Reset
                    </button>
                </form>
                <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
