<x-mail::message>
<img src="{{ config('app.url') }}/images/logo.webp" alt="{{ config('app.name') }}" style="height:30px;margin-bottom:15px">

# Booking Dibatalkan

Halo **{{ $booking->user->name }}**,

Booking **{{ $booking->booking_number }}** untuk {{ $booking->items->count() > 1 ? 'unit-unit berikut' : 'unit' }} **{{ $booking->units_list }}** telah dibatalkan.

<x-mail::table>
| Detail | |
|--------|---|
| **No. Booking** | {{ $booking->booking_number }} |
| **Unit** | {{ $booking->units_list }} |
| **Tanggal** | {{ $booking->start_date->format('d M Y') }} — {{ $booking->end_date->format('d M Y') }} |
| **Status** | {{ $booking->status_label }} |
</x-mail::table>

Jika Anda sudah melakukan pembayaran, dana akan dikembalikan sesuai kebijakan refund kami.

<x-mail::button :url="route('bookings.show', $booking)">
Lihat Detail
</x-mail::button>

Terima kasih telah menggunakan RentFlow.
</x-mail::message>
