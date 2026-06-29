@extends('layouts.app')

@section('title', 'Syarat & Ketentuan')

@section('content')
<div class="mx-auto max-w-7xl card p-8">
    <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 mb-6">Syarat & Ketentuan</h1>

    <p class="mb-4 text-slate-600">Terakhir diperbarui: {{ date('d M Y') }}</p>

    <div class="prose prose-indigo max-w-none text-slate-700 dark:text-slate-200">
        <p>Selamat datang di RentFlow. Dengan mengakses atau menggunakan layanan kami, Anda setuju untuk terikat oleh Syarat dan Ketentuan berikut. Jika Anda tidak setuju dengan bagian mana pun dari syarat-syarat ini, Anda tidak diperbolehkan menggunakan layanan kami.</p>

        <h2>1. Penggunaan Layanan</h2>
        <p>Anda setuju untuk menggunakan layanan kami hanya untuk tujuan yang sah dan sesuai dengan Syarat dan Ketentuan ini. Anda bertanggung jawab untuk menjaga kerahasiaan informasi akun dan kata sandi Anda.</p>

        <h2>2. Pendaftaran dan Akun</h2>
        <p>Untuk menggunakan fitur tertentu dari layanan kami, Anda mungkin perlu mendaftar untuk mendapatkan akun. Anda setuju untuk memberikan informasi yang akurat, lengkap, dan terkini selama proses pendaftaran dan untuk memperbarui informasi tersebut agar tetap akurat, lengkap, dan terkini.</p>

        <h2>3. Pemesanan (Booking)</h2>
        <p>Semua pemesanan unit melalui layanan kami bergantung pada ketersediaan. Kami berhak untuk menolak atau membatalkan pemesanan apa pun karena alasan apa pun, termasuk kesalahan informasi atau ketersediaan unit.</p>

        <h2>4. Pembayaran</h2>
        <p>Pembayaran untuk layanan kami diproses melalui gerbang pembayaran pihak ketiga (Midtrans). Dengan melakukan pembayaran, Anda setuju untuk mematuhi syarat dan ketentuan penyedia layanan pembayaran tersebut. Harga dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>

        <h2>5. Pembatalan dan Pengembalian Dana</h2>
        <p>Kebijakan pembatalan dan pengembalian dana kami berlaku untuk semua pemesanan. Rincian spesifik mengenai pembatalan dan pengembalian dana dapat ditemukan di halaman detail unit atau selama proses pemesanan. Admin memiliki hak mutlak dalam menentukan status pengembalian dana.</p>

        <h2>6. Tanggung Jawab Pengguna</h2>
        <p>Anda bertanggung jawab penuh atas penggunaan unit yang Anda sewa. Setiap kerusakan, kehilangan, atau masalah hukum yang timbul selama masa sewa adalah tanggung jawab penyewa. Anda setuju untuk mematuhi semua hukum dan peraturan setempat yang berlaku.</p>

        <h2>7. Hak Kekayaan Intelektual</h2>
        <p>Layanan kami dan konten aslinya, fitur, dan fungsionalitasnya adalah dan akan tetap menjadi milik eksklusif RentFlow dan pemberi lisensinya.</p>

        <h2>8. Batasan Tanggung Jawab</h2>
        <p>Dalam hal apa pun RentFlow tidak akan bertanggung jawab atas kerusakan tidak langsung, insidental, khusus, konsekuensial, atau hukuman, termasuk namun tidak terbatas pada, kehilangan laba, data, penggunaan, niat baik, atau kerugian tidak berwujud lainnya, yang timbul dari penggunaan layanan kami.</p>

        <h2>9. Perubahan Syarat dan Ketentuan</h2>
        <p>Kami berhak, atas kebijakan kami sendiri, untuk mengubah atau mengganti Syarat dan Ketentuan ini kapan saja. Jika revisi bersifat material, kami akan mencoba memberikan pemberitahuan setidaknya 30 hari sebelum syarat baru berlaku.</p>

        <h2>10. Hukum yang Mengatur</h2>
        <p>Syarat dan Ketentuan ini akan diatur dan ditafsirkan sesuai dengan hukum Republik Indonesia, tanpa memperhatikan pertentangan ketentuan hukumnya.</p>

        <h2>Hubungi Kami</h2>
        <p>Jika Anda memiliki pertanyaan tentang Syarat dan Ketentuan ini, silakan hubungi kami di [Alamat Email Dukungan Anda].</p>
    </div>
</div>
@endsection
