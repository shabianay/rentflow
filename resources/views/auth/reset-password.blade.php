@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="flex min-h-[calc(100vh-8rem)] items-center justify-center">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl text-2xl font-bold text-white shadow-lg shadow-indigo-200">
                    <img src="{{ asset('images/logo.webp') }}" alt="RentFlow" class="h-8 w-8 rounded-lg object-cover">
                </div>
                <h1 class="mt-4 text-2xl font-bold text-slate-800 dark:text-slate-100">Reset Password</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Buat password baru untuk akun Anda</p>
            </div>
            <div class="card">
                <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-1.5 input-field @error('email') border-red-300 dark:border-red-500 @enderror"
                            value="{{ old('email', $email) }}" readonly required>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="mt-1.5 input-field @error('password') border-red-300 dark:border-red-500 @enderror"
                            placeholder="Minimal 8 karakter" required>
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1.5 input-field @error('password') border-red-300 dark:border-red-500 @enderror"
                            placeholder="Ulangi password baru" required>
                    </div>
                    <button type="submit" class="btn-primary w-full">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
