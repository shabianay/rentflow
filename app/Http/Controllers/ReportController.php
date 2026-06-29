<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');
        $query = Booking::query();

        $now = Carbon::now();

        match ($period) {
            'today' => $query->whereDate('created_at', $now),
            'week' => $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
            'year' => $query->whereYear('created_at', $now->year),
            default => null,
        };

        $totalRevenue = (clone $query)->whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_amount');
        $bookingCount = (clone $query)->count();
        $paidBookings = (clone $query)->whereIn('status', ['confirmed', 'active', 'completed'])->count();
        $cancelledBookings = (clone $query)->where('status', 'cancelled')->count();
        $pendingBookings = (clone $query)->where('status', 'pending')->count();

        $paidRevenue = (clone $query)->whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_amount');
        $averageBookingValue = $paidBookings > 0 ? $paidRevenue / $paidBookings : 0;

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $months[] = $month->format('M Y');
            $revenue = Booking::whereIn('status', ['confirmed', 'active', 'completed'])
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');
            $monthlyRevenue[] = $revenue;
        }

        // Top performing units
        $topUnits = Unit::withCount(['bookings as completed_bookings' => fn ($q) => $q->whereIn('status', ['confirmed', 'active', 'completed'])])
            ->withSum(['bookings as revenue' => fn ($q) => $q->whereIn('status', ['confirmed', 'active', 'completed'])], 'total_amount')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'unit'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'bookingCount',
            'paidBookings',
            'cancelledBookings',
            'pendingBookings',
            'averageBookingValue',
            'months',
            'monthlyRevenue',
            'topUnits',
            'recentBookings',
            'period',
        ));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'all');
        $query = Booking::with(['user', 'unit']);

        $now = Carbon::now();
        match ($period) {
            'today' => $query->whereDate('created_at', $now),
            'week' => $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
            'year' => $query->whereYear('created_at', $now->year),
            default => null,
        };

        $bookings = $query->latest()->get();

        $filename = 'bookings_report_' . $now->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No. Booking', 'Customer', 'Unit', 'Tanggal Mulai', 'Tanggal Selesai', 'Durasi', 'Status', 'Total']);

            foreach ($bookings as $b) {
                fputcsv($handle, [
                    $b->booking_number,
                    $b->user->name,
                    $b->unit->name,
                    $b->start_date->format('Y-m-d'),
                    $b->end_date->format('Y-m-d'),
                    $b->total_days . ' hari',
                    $b->status,
                    $b->total_amount,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
