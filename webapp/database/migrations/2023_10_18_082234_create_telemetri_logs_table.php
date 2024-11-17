<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('telemetri_logs', function (Blueprint $table) {
            $table->id();
            $table->string('tReceived')->nullable();
            $table->string('tPayload')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('alt')->nullable();
            $table->string('sog')->nullable();
            $table->string('cog')->nullable();
            $table->string('arus')->nullable();
            $table->string('tegangan')->nullable();
            $table->string('daya')->nullable();
            $table->string('klasifikasi')->nullable();
            $table->string('ax')->nullable();
            $table->string('ay')->nullable();
            $table->string('az')->nullable();
            $table->string('gx')->nullable();
            $table->string('gy')->nullable();
            $table->string('gz')->nullable();
            $table->string('mx')->nullable();
            $table->string('my')->nullable();
            $table->string('mz')->nullable();
            $table->string('roll')->nullable();
            $table->string('pitch')->nullable();
            $table->string('yaw')->nullable();
            $table->string('suhu')->nullable();
            $table->string('humidity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetri_logs');
    }
};
