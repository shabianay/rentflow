<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@rentflow.test'],
            ['name' => 'RentFlow Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );
        $admin = User::firstOrCreate(
            ['email' => 'shabianarsyl@gmail.com'],
            ['name' => 'Shabian', 'password' => Hash::make('password'), 'role' => 'customer']
        );

        $categories = collect(['Mobil', 'Motor', 'Kamera', 'Camping', 'Sound System', 'Lainnya'])->mapWithKeys(function ($name) {
            return [$name => Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])];
        });

        Unit::firstOrCreate(
            ['asset_number' => 'CAR-001'],
            [
                'category_id' => $categories['Mobil']->id,
                'name' => 'Toyota Avanza 2023',
                'slug' => 'toyota-avanza-2023',
                'description' => 'Mobil keluarga irit dan nyaman untuk perjalanan harian.',
                'price_per_day' => 450000,
                'deposit_amount' => 1000000,
                'status' => 'ready',
                'location' => 'Jakarta Selatan',
            ]
        );

        Unit::firstOrCreate(
            ['asset_number' => 'CAM-001'],
            [
                'category_id' => $categories['Kamera']->id,
                'name' => 'Sony A7 III Kit',
                'slug' => 'sony-a7-iii-kit',
                'description' => 'Kamera mirrorless full-frame untuk foto dan video profesional.',
                'price_per_day' => 300000,
                'deposit_amount' => 1500000,
                'status' => 'ready',
                'location' => 'Bandung',
            ]
        );
    }
}
