<x-mail::message>
<img src="{{ config('app.url') }}/images/logo.webp" alt="{{ config('app.name') }}" style="height:30px;margin-bottom:15px">

# Pembayaran Berhasil

Halo **{{ $payment->booking->user->name }}**,

Pembayaran Anda untuk booking **{{ $payment->booking->booking_number }}** telah berhasil diproses.

<x-mail::table>
| Detail | |
|--------|---|
| **No. Pembayaran** | {{ $payment->payment_number }} |
| **No. Booking** | {{ $payment->booking->booking_number }} |
| **Unit** | {{ $payment->booking->units_list }} |
| **Jumlah** | Rp {{ number_format($payment->amount, 0, ',', '.') }} |
| **Status** | {{ ucfirst($payment->status) }} |
| **Tanggal Bayar** | {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }} |
</x-mail::table>

<x-mail::button :url="route('bookings.show', $payment->booking)">
Lihat Booking
</x-mail::button>

Terima kasih telah menggunakan RentFlow.
</x-mail::message>
