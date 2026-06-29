@extends('layouts.app')

@section('title', 'Register')



@section('content')
    <div class="flex min-h-[calc(100vh-8rem)] items-center justify-center py-8">
        <div class="w-full max-w-2xl">
            <div class="mb-8 text-center">
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl text-2xl font-bold text-white shadow-lg shadow-indigo-200">
                    <img src="{{ asset('images/logo.webp') }}" alt="RentFlow" class="h-8 w-8 rounded-lg object-cover">
                </div>
                <h1 class="mt-4 text-2xl font-bold text-slate-800 dark:text-slate-100">Buat Akun Baru</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Isi data diri Anda untuk mulai menyewa unit</p>
            </div>
            <div class="card">
                <form method="post" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="mt-1.5 input-field"
                                placeholder="John Doe" value="{{ old('name') }}" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                            <input type="email" name="email" id="email"
                                class="mt-1.5 input-field @error('email') border-red-300 @enderror"
                                placeholder="nama@email.com" value="{{ old('email') }}" required>
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="place_of_birth" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tempat
                                Lahir</label>
                            <input type="text" name="place_of_birth" id="place_of_birth" class="mt-1.5 input-field"
                                placeholder="Jakarta" value="{{ old('place_of_birth') }}" required>
                        </div>
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tanggal
                                Lahir</label>
                            <input type="text" name="date_of_birth" id="date_of_birth" class="mt-1.5 input-field"
                                placeholder="Pilih tanggal lahir" value="{{ old('date_of_birth') }}" required readonly>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor WhatsApp</label>
                            <input type="tel" name="phone" id="phone" class="mt-1.5 input-field"
                                placeholder="08123456789" value="{{ old('phone') }}" required>
                        </div>
                        <div>
                            <label for="id_card_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor KTP</label>
                            <input type="text" name="id_card_number" id="id_card_number"
                                class="mt-1.5 input-field @error('id_card_number') border-red-300 @enderror"
                                placeholder="317xxxxxxxxxxxxx" value="{{ old('id_card_number') }}" required>
                            @error('id_card_number')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Alamat</label>
                            <textarea name="address" id="address" rows="2" class="mt-1.5 input-field"
                                placeholder="Jl. Contoh No. 123, RT/RW 001/002, Kelurahan, Kecamatan, Kota" required>{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                            <input type="password" name="password" id="password" class="mt-1.5 input-field"
                                placeholder="Minimal 8 karakter" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1.5 input-field" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">Daftar</button>
                </form>
                <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Masuk</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
  function initDatePicker() {
    if (typeof flatpickr === 'undefined') {
      var s = document.createElement('script');
      s.src = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js';
      s.onload = initDatePicker;
      document.head.appendChild(s);
      return;
    }
    flatpickr('#date_of_birth', {
      maxDate: 'today',
      dateFormat: 'Y-m-d',
    });
  }
  document.addEventListener('DOMContentLoaded', initDatePicker);
</script>
@endpush
