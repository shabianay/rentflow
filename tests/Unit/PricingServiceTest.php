<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\PricingService;
use App\Models\Unit;
use Carbon\Carbon;

class PricingServiceTest extends TestCase
{
    protected PricingService $pricingService;
    protected Unit $unit;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pricingService = new PricingService();
        $this->unit = new Unit([
            'price_per_day' => 100,
            'price_per_week' => 600,
            'price_per_month' => 2500,
            'deposit_amount' => 50,
            'weekend_price' => 120,
        ]);
    }

    public function test_daily_pricing(): void
    {
        $startDate = Carbon::parse('2026-07-01'); // Wednesday
        $endDate = Carbon::parse('2026-07-01'); // same day, 1 inclusive day
        $result = $this->pricingService->calculateTotal($this->unit, $startDate, $endDate);
        // 1 day * 100 + 50 deposit = 150
        $this->assertEquals(150, $result['total']);
        $this->assertEquals(100, $result['subtotal']);
        $this->assertEquals(50, $result['deposit']);
        $this->assertEquals(1, $result['days']);
    }

    public function test_weekly_pricing(): void
    {
        $startDate = Carbon::parse('2026-07-01'); // Wednesday
        $endDate = Carbon::parse('2026-07-07'); // Tuesday, 7 inclusive days
        $result = $this->pricingService->calculateTotal($this->unit, $startDate, $endDate);
        // 1 week * 600 + 50 deposit = 650
        $this->assertEquals(650, $result['total']);

        $endDate = Carbon::parse('2026-07-08'); // Wednesday, 8 inclusive days (1 week + 1 day)
        $result = $this->pricingService->calculateTotal($this->unit, $startDate, $endDate);
        // (1 week * 600) + (1 day * 100) + 50 deposit = 750
        $this->assertEquals(750, $result['total']);
    }

    public function test_monthly_pricing(): void
    {
        $startDate = Carbon::parse('2026-07-01'); // Wednesday
        $endDate = Carbon::parse('2026-07-30'); // Thursday, 30 inclusive days
        $result = $this->pricingService->calculateTotal($this->unit, $startDate, $endDate);
        // 1 month * 2500 + 50 deposit = 2550
        $this->assertEquals(2550, $result['total']);

        $endDate = Carbon::parse('2026-08-02'); // Sunday, 33 inclusive days (1 month + 3 remaining)
        // Remaining from Aug 1: Aug 1(Sat=120), Aug 2(Sun=120), Aug 3(Mon=100)
        // (1 month * 2500) + (120+120+100) + 50 deposit = 2500 + 340 + 50 = 2890
        $result = $this->pricingService->calculateTotal($this->unit, $startDate, $endDate);
        $this->assertEquals(2890, $result['total']);
    }

    public function test_weekend_pricing(): void
    {
        // 2026-07-04 is Saturday, 2026-07-05 is Sunday
        $startDate = Carbon::parse('2026-07-03'); // Friday
        $endDate = Carbon::parse('2026-07-05'); // Sunday, 3 inclusive days (Fri, Sat, Sun)
        $result = $this->pricingService->calculateTotal($this->unit, $startDate, $endDate);
        // (1 day * 100) + (2 days * 120) + 50 deposit = 100 + 240 + 50 = 390
        $this->assertEquals(390, $result['total']);

        // Test with no weekend_price set
        $unitWithoutWeekendPrice = new Unit([
            'price_per_day' => 100,
            'price_per_week' => 600,
            'price_per_month' => 2500,
            'deposit_amount' => 50,
            'weekend_price' => null,
        ]);
        $resultWithoutWeekend = $this->pricingService->calculateTotal($unitWithoutWeekendPrice, $startDate, $endDate);
        // (3 days * 100) + 50 deposit = 350
        $this->assertEquals(350, $resultWithoutWeekend['total']);
    }

    public function test_pricing_includes_deposit(): void
    {
        $startDate = Carbon::parse('2026-07-01');
        $endDate = Carbon::parse('2026-07-01');
        $result = $this->pricingService->calculateTotal($this->unit, $startDate, $endDate);
        // 1 day * 100 + 50 deposit = 150
        $this->assertEquals(150, $result['total']);

        // Unit with 0 deposit
        $unitWithoutDeposit = new Unit([
            'price_per_day' => 100,
            'deposit_amount' => 0,
        ]);
        $resultWithoutDeposit = $this->pricingService->calculateTotal($unitWithoutDeposit, $startDate, $endDate);
        // 1 day * 100 + 0 deposit = 100
        $this->assertEquals(100, $resultWithoutDeposit['total']);
    }
}
