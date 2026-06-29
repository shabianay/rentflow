<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'photos', 'price_per_day', 'price_per_week', 'price_per_month', 'weekend_price', 'holiday_price', 'deposit_amount', 'status', 'location', 'asset_number', 'is_active'];

    protected $casts = ['photos' => 'array', 'is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function isAvailable(string $startDate, string $endDate): bool
    {
        if (! $this->is_active || in_array($this->status, ['maintenance', 'reserved'], true)) {
            return false;
        }

        return ! $this->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();
    }
}
