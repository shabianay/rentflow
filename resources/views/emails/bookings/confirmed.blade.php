<x-mail::message>
<img src="{{ config('app.url') }}/images/logo.webp" alt="{{ config('app.name') }}" style="height:30px;margin-bottom:15px">

# Booking Dikonfirmasi

Halo **{{ $booking->user->name }}**,

Booking Anda telah berhasil dibuat dan menunggu pembayaran.

<x-mail::table>
| Detail | |
|--------|---|
| **No. Booking** | {{ $booking->booking_number }} |
| **Unit** | {{ $booking->units_list }} |
| **Tanggal Mulai** | {{ $booking->start_date->format('d M Y') }} |
| **Tanggal Selesai** | {{ $booking->end_date->format('d M Y') }} |
| **Durasi** | {{ $booking->total_days }} hari |
| **Total** | Rp {{ number_format($booking->total_amount, 0, ',', '.') }} |
| **Status** | {{ $booking->status_label }} |
</x-mail::table>

Silakan lanjutkan pembayaran untuk mengkonfirmasi booking Anda.

<x-mail::button :url="route('payments.show', $booking)">
Bayar Sekarang
</x-mail::button>

Terima kasih telah menggunakan RentFlow.
</x-mail::message>
