<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['booking_number', 'user_id', 'unit_id', 'start_date', 'end_date', 'total_days', 'subtotal', 'discount', 'deposit_amount', 'total_amount', 'status', 'notes'];

    public static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getRouteKey(): string
    {
        return $this->uuid ?? (string) $this->id;
    }

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'confirmed' => 'Dikonfirmasi',
            'active' => 'Lunas',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getUnitsListAttribute(): string
    {
        $names = $this->items()->with('unit')->get()->pluck('unit.name')->filter();

        if ($names->isEmpty()) {
            return $this->unit?->name ?? '-';
        }

        if ($names->count() === 1) {
            return $names->first();
        }

        return $names->first() . ' (+' . ($names->count() - 1) . ' lagi)';
    }

    public function syncUnitStatus(string $status): void
    {
        $unitIds = $this->items()->pluck('unit_id');

        if ($unitIds->isEmpty()) {
            Unit::where('id', $this->unit_id)->update(['status' => $status]);
            return;
        }

        Unit::whereIn('id', $unitIds)->update(['status' => $status]);
    }
}
