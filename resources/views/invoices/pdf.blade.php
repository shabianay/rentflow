<!doctype html>
<html lang="id"><head><meta charset="utf-8"><title>Invoice {{ $invoice->invoice_number }}</title>
<style>body{font-family:sans-serif;padding:40px;max-width:700px;margin:auto}table{width:100%;border-collapse:collapse}td,th{padding:10px;border:1px solid #ddd;text-align:left}.right{text-align:right}.bold{font-weight:bold}</style></head>
<body>
    <img src="{{ public_path('images/logo.webp') }}" style="height:40px;margin-bottom:8px">
    <h1 style="margin:0">RentFlow</h1>
    <div style="color:#666;font-size:13px">Invoice #{{ $invoice->invoice_number }}</div>
    <div style="color:#666;font-size:13px">{{ $invoice->created_at->format('d M Y') }}</div>
    <hr style="margin:20px 0">
    <div><strong>Customer:</strong> {{ $invoice->user->name }} ({{ $invoice->user->email }})</div>
    <table style="margin-top:20px"><thead><tr><th>Deskripsi</th><th>Jumlah</th></tr></thead><tbody>
        @forelse ($invoice->booking->items as $item)
            <tr><td>{{ $item->unit->name }} — {{ $item->days }} hari x Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td><td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td></tr>
        @empty
            <tr><td>{{ $invoice->booking->units_list }} ({{ $invoice->booking->start_date->format('d/m/Y') }} - {{ $invoice->booking->end_date->format('d/m/Y') }})</td><td class="right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td></tr>
        @endforelse
    </tbody></table>
    <div style="margin-top:20px;text-align:right;font-size:18px;font-weight:bold">Total: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
    <hr style="margin-top:40px">
    <div style="font-size:12px;color:#666">Terima kasih telah menggunakan RentFlow.</div>
</body></html>
