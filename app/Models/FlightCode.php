<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flight_code',
    ];

    /**
     * Get all of the telemetri_logs for the FlightCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function telemetri_logs(): HasMany
    {
        return $this->hasMany(TelemetriLog::class);
    }
}
