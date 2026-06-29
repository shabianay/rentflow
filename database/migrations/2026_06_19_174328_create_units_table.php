<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('price_per_week', 12, 2)->nullable();
            $table->decimal('price_per_month', 12, 2)->nullable();
            $table->decimal('weekend_price', 12, 2)->nullable();
            $table->decimal('holiday_price', 12, 2)->nullable();
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->enum('status', ['ready', 'booked', 'on_rent', 'maintenance', 'reserved'])->default('ready');
            $table->string('location')->nullable();
            $table->string('asset_number')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
