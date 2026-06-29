<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'price_per_day' => fake()->numberBetween(50000, 500000),
            'deposit_amount' => fake()->numberBetween(25000, 100000),
            'asset_number' => 'AST-' . strtoupper(fake()->bothify('????####')),
            'status' => 'ready',
            'is_active' => true,
        ];
    }
}
