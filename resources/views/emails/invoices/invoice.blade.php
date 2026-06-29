<x-mail::message>
<img src="{{ config('app.url') }}/images/logo.webp" alt="{{ config('app.name') }}" style="height:30px;margin-bottom:15px">

# Invoice

Halo **{{ $invoice->user->name }}**,

Berikut adalah invoice untuk booking Anda.

<x-mail::table>
| Detail | |
|--------|---|
| **No. Invoice** | {{ $invoice->invoice_number }} |
| **No. Booking** | {{ $invoice->booking->booking_number }} |
| **Unit** | {{ $invoice->booking->units_list }} |
| **Jumlah** | Rp {{ number_format($invoice->amount, 0, ',', '.') }} |
| **Status** | {{ ucfirst($invoice->status) }} |
</x-mail::table>

<x-mail::button :url="route('invoices.show', $invoice)">
Lihat Invoice
</x-mail::button>

Terima kasih telah menggunakan RentFlow.
</x-mail::message>
