<?php

namespace App\Services;

use App\Models\Unit;
use Carbon\Carbon;

class PricingService
{
    public function calculateTotal(Unit $unit, string $startDate, string $endDate, int &$days = null): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end) + 1;

        $subtotal = 0;
        $breakdown = [];

        if ($days >= 30 && $unit->price_per_month) {
            $months = intdiv($days, 30);
            $remaining = $days % 30;
            $subtotal += $months * $unit->price_per_month;
            $breakdown[] = ['label' => "{$months} bulan", 'amount' => $months * $unit->price_per_month];
            if ($remaining > 0) {
                $dailyPart = $this->calculateDaily($unit, $start->copy()->addMonths($months), $remaining, $breakdown);
                $subtotal += $dailyPart;
            }
        } elseif ($days >= 7 && $unit->price_per_week) {
            $weeks = intdiv($days, 7);
            $remaining = $days % 7;
            $subtotal += $weeks * $unit->price_per_week;
            $breakdown[] = ['label' => "{$weeks} minggu", 'amount' => $weeks * $unit->price_per_week];
            if ($remaining > 0) {
                $dailyPart = $this->calculateDaily($unit, $start->copy()->addWeeks($weeks), $remaining, $breakdown);
                $subtotal += $dailyPart;
            }
        } else {
            $subtotal = $this->calculateDaily($unit, $start, $days, $breakdown);
        }

        $deposit = $unit->deposit_amount ?? 0;
        $total = $subtotal + $deposit;

        return compact('subtotal', 'deposit', 'total', 'days', 'breakdown');
    }

    private function calculateDaily(Unit $unit, Carbon $cursor, int $days, array &$breakdown): float
    {
        $total = 0;
        for ($i = 0; $i < $days; $i++) {
            $dayOfWeek = $cursor->dayOfWeek;
            $isWeekend = in_array($dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY], true);
            $price = $unit->price_per_day;

            if ($isWeekend && $unit->weekend_price) {
                $price = $unit->weekend_price;
            }

            $total += $price;
            $cursor->addDay();
        }
        $breakdown[] = ['label' => "{$days} hari", 'amount' => $total];
        return $total;
    }
}
