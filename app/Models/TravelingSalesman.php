<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class TravelingSalesman extends Model
{
    use HasFactory;

    /**
     * The attributes that guarded.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];


    /**
     * Get the flight_code that owns the TelemetriLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function telemetry_log(): BelongsTo
    {
        return $this->belongsTo(TelemetriLog::class);
    }
}
