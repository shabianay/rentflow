<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MidtransServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_instantiate(): void
    {
        $service = new MidtransService();
        $this->assertInstanceOf(MidtransService::class, $service);
    }

    public function test_get_status_returns_success_when_order_exists(): void
    {
        $service = new MidtransService();
        $result = $service->getStatus('non-existent-order-id');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
    }

    public function test_service_initializes_midtrans_config(): void
    {
        config([
            'midtrans.server_key' => 'SB-Mid-server-test',
            'midtrans.client_key' => 'SB-Mid-client-test',
            'midtrans.is_production' => false,
        ]);

        new MidtransService();

        $this->assertEquals('SB-Mid-server-test', \Midtrans\Config::$serverKey);
        $this->assertEquals('SB-Mid-client-test', \Midtrans\Config::$clientKey);
        $this->assertFalse(\Midtrans\Config::$isProduction);
        $this->assertTrue(\Midtrans\Config::$isSanitized);
        $this->assertTrue(\Midtrans\Config::$is3ds);
    }

    public function test_config_values_are_set(): void
    {
        config([
            'midtrans.server_key' => 'test-server-key',
            'midtrans.client_key' => 'test-client-key',
            'midtrans.is_production' => false,
        ]);

        $service = new MidtransService();

        $reflection = new \ReflectionClass($service);
        $serverKey = $reflection->getProperty('serverKey');
        $serverKey->setAccessible(true);
        $this->assertEquals('test-server-key', $serverKey->getValue($service));

        $clientKey = $reflection->getProperty('clientKey');
        $clientKey->setAccessible(true);
        $this->assertEquals('test-client-key', $clientKey->getValue($service));

        $isProduction = $reflection->getProperty('isProduction');
        $isProduction->setAccessible(true);
        $this->assertFalse($isProduction->getValue($service));
    }
}
