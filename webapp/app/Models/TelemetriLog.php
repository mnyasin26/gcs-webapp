<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelemetriLog extends Model
{
    use HasFactory;

    /**
     * The attributes that guarded.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    /**
     * Get the telemetriLog's tPayload.
     */
    protected function tPayload(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtotime($value),
        );
    }

    /**
     * Get the garden_profile that owns the TelemetriLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function garden_profile(): BelongsTo
    {
        return $this->belongsTo(GardenProfile::class);
    }

    /**
     * Get the flight_code that owns the TelemetriLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function flight_code(): BelongsTo
    {
        return $this->belongsTo(FlightCode::class);
    }
}
