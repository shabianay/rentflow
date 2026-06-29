@extends(Auth::user()->role === 'admin' ? 'layouts.admin' : 'layouts.app')

@section('title', 'Edit Profil')



@if (Auth::user()->role === 'admin')
    @section('page_title', 'Profil')
    @section('page_subtitle', 'Edit data diri Anda')
@endif

@section('content')
<div>
    @if (Auth::user()->role !== 'admin')
        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Profil</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Perbarui data diri Anda</p>
        </div>
    @endif

    <div class="card">
        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="mt-1.5 input-field" value="{{ old('name', $user->name) }}" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                    <input type="email" name="email" id="email" class="mt-1.5 input-field @error('email') border-red-300 @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="place_of_birth" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tempat Lahir</label>
                    <input type="text" name="place_of_birth" id="place_of_birth" class="mt-1.5 input-field" value="{{ old('place_of_birth', $user->place_of_birth) }}">
                </div>
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tanggal Lahir</label>
                    <input type="text" name="date_of_birth" id="date_of_birth" class="mt-1.5 input-field" placeholder="Pilih tanggal lahir" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" readonly>
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor WhatsApp</label>
                    <input type="tel" name="phone" id="phone" class="mt-1.5 input-field" value="{{ old('phone', $user->phone) }}" placeholder="08123456789">
                </div>
                <div>
                    <label for="id_card_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor KTP</label>
                    <input type="text" name="id_card_number" id="id_card_number" class="mt-1.5 input-field @error('id_card_number') border-red-300 @enderror" value="{{ old('id_card_number', $user->id_card_number) }}">
                    @error('id_card_number')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Alamat</label>
                    <textarea name="address" id="address" rows="2" class="mt-1.5 input-field">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="mt-1.5 input-field" placeholder="Kosongi jika tidak ingin ganti password">
                    @error('current_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div></div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" class="mt-1.5 input-field" placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="mt-1.5 input-field" placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
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
