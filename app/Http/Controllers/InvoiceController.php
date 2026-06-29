<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $invoices = Invoice::with('booking.unit')
            ->where('user_id', Auth::id())
            ->when($search, fn ($q) => $q->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('booking.unit', fn ($u) => $u->where('name', 'like', "%{$search}%")))
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices', 'search'));
    }

    public function adminIndex(Request $request)
    {
        $search = $request->get('search');
        $invoices = Invoice::with(['booking.unit', 'user'])
            ->when($search, fn ($q) => $q->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                ->orWhereHas('booking', fn ($b) => $b->where('booking_number', 'like', "%{$search}%")))
            ->latest()
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices', 'search'));
    }

    public function show(Invoice $invoice)
    {
        abort_if($invoice->user_id !== Auth::id(), 403);

        $invoice->load(['booking.unit', 'booking.items.unit', 'user']);

        return view('invoices.show', compact('invoice'));
    }

    public function adminShow(Invoice $invoice)
    {
        $invoice->load(['booking.unit', 'booking.items.unit', 'user']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        abort_if($invoice->user_id !== Auth::id() && Auth::user()->role !== 'admin', 403);

        $invoice->load(['booking.unit', 'booking.items.unit', 'user']);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download($invoice->invoice_number.'.pdf');
    }
}
