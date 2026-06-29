@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@section('content')
<div class="mx-auto max-w-7xl card p-8">
    <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 mb-6">Kebijakan Privasi</h1>

    <p class="mb-4 text-slate-600">Terakhir diperbarui: {{ date('d M Y') }}</p>

    <div class="prose prose-indigo max-w-none text-slate-700 dark:text-slate-200">
        <p>Selamat datang di Kebijakan Privasi RentFlow. Privasi Anda sangat penting bagi kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, mengungkapkan, dan melindungi informasi Anda ketika Anda menggunakan layanan kami.</p>

        <h2>Informasi yang Kami Kumpulkan</h2>
        <p>Kami mengumpulkan berbagai jenis informasi untuk menyediakan dan meningkatkan layanan kami kepada Anda:</p>
        <ul>
            <li><strong>Informasi Pribadi:</strong> Nama, alamat email, nomor telepon, alamat, tanggal lahir, tempat lahir, nomor KTP, dll., yang Anda berikan saat mendaftar, membuat booking, atau mengisi profil.</li>
            <li><strong>Informasi Transaksi:</strong> Detail booking, informasi pembayaran (melalui Midtrans), total biaya.</li>
            <li><strong>Informasi Penggunaan:</strong> Data tentang bagaimana Anda mengakses dan menggunakan layanan kami, seperti alamat IP, jenis browser, halaman yang dikunjungi, waktu dan tanggal kunjungan.</li>
        </ul>

        <h2>Bagaimana Kami Menggunakan Informasi Anda</h2>
        <p>Kami menggunakan informasi yang kami kumpulkan untuk berbagai tujuan, termasuk:</p>
        <ul>
            <li>Menyediakan, mengoperasikan, dan memelihara layanan kami.</li>
            <li>Memproses transaksi Anda dan mengirimkan pemberitahuan terkait.</li>
            <li>Meningkatkan, mempersonalisasi, dan memperluas layanan kami.</li>
            <li>Memahami dan menganalisis bagaimana Anda menggunakan layanan kami.</li>
            <li>Mengembangkan produk, layanan, fitur, dan fungsionalitas baru.</li>
            <li>Berkomunikasi dengan Anda, baik secara langsung maupun melalui salah satu mitra kami, termasuk untuk layanan pelanggan, untuk memberi Anda pembaruan dan informasi lain yang berkaitan dengan layanan, dan untuk tujuan pemasaran dan promosi.</li>
            <li>Melakukan deteksi dan pencegahan penipuan.</li>
        </ul>

        <h2>Pengungkapan Informasi Anda</h2>
        <p>Kami mungkin membagikan informasi yang kami kumpulkan dalam berbagai situasi:</p>
        <ul>
            <li><strong>Dengan Penyedia Layanan:</strong> Kami dapat membagikan informasi Anda dengan pihak ketiga yang menyediakan layanan untuk kami, seperti pemroses pembayaran (Midtrans), penyedia hosting, layanan pengiriman email, dan analisis data.</li>
            <li><strong>Untuk Kepatuhan Hukum:</strong> Kami dapat mengungkapkan informasi Anda jika diwajibkan oleh hukum atau sebagai respons terhadap permintaan hukum yang sah.</li>
            <li><strong>Dengan Persetujuan Anda:</strong> Kami dapat mengungkapkan informasi Anda untuk tujuan lain dengan persetujuan Anda.</li>
        </ul>

        <h2>Keamanan Informasi Anda</h2>
        <p>Kami menggunakan langkah-langkah keamanan administratif, teknis, dan fisik untuk membantu melindungi informasi pribadi Anda. Meskipun kami telah mengambil langkah-langkah yang wajar untuk mengamankan informasi pribadi yang Anda berikan kepada kami, perlu diketahui bahwa terlepas dari upaya kami, tidak ada tindakan keamanan yang sempurna atau tidak dapat ditembus, dan tidak ada metode transmisi data yang dapat dijamin terhadap intersepsi atau jenis penyalahgunaan lainnya.</p>

        <h2>Pilihan Anda Mengenai Informasi Anda</h2>
        <p>Anda dapat kapan saja meninjau atau mengubah informasi di akun Anda atau mengakhiri akun Anda dengan menghubungi kami menggunakan informasi kontak yang disediakan di bawah.</p>

        <h2>Perubahan Kebijakan Privasi Ini</h2>
        <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Kami akan memberi tahu Anda tentang setiap perubahan dengan memposting Kebijakan Privasi yang baru di halaman ini. Kami menyarankan Anda untuk meninjau Kebijakan Privasi ini secara berkala untuk setiap perubahan. Perubahan pada Kebijakan Privasi ini efektif saat diposting di halaman ini.</p>

        <h2>Hubungi Kami</h2>
        <p>Jika Anda memiliki pertanyaan atau komentar tentang Kebijakan Privasi ini, silakan hubungi kami melalui [Alamat Email Dukungan Anda] atau [Nomor Telepon Dukungan Anda].</p>
    </div>
</div>
@endsection
