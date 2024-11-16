<?php

use App\Http\Controllers\FlightCodeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\TelemetriLogController;
use App\Http\Controllers\TravelingSalesmanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('telemetri_logs/selected', [TelemetriLogController::class, 'selected'])->name('telemetri_logs.selected');

Route::get('telemetri_logs', [TelemetriLogController::class, 'index'])->name('telemetri_logs.index');
Route::get('telemetri_logs/{telemetriLog}', [TelemetriLogController::class, 'show'])->name('telemetri_logs.show');
Route::post('telemetri_logs', [TelemetriLogController::class, 'store'])->name('telemetri_logs.store');
Route::put('telemetri_logs/{telemetriLog}', [TelemetriLogController::class, 'update'])->name('telemetri_logs.update');
Route::delete('telemetri_logs/{telemetriLog}', [TelemetriLogController::class, 'destroy'])->name('telemetri_logs.delete');

Route::post('flight-code/select-view', [MapController::class, 'select_view_api'])->name('flight_code.select_view_api');
Route::get('tsp/{flight_code}', [MapController::class, 'tsp_api'])->name('map.tsp');
Route::post('signal-api', [MapController::class, 'signal_api'])->name('map.signal');

Route::get('traveling-salesman/data/{flight_code}', [TravelingSalesmanController::class, 'get_data'])->name('traveling_salesman.get_data');
Route::post('traveling-salesman/store', [TravelingSalesmanController::class, 'store'])->name('traveling_salesman.store');
Route::post('traveling-salesman/draw', [TravelingSalesmanController::class, 'draw'])->name('traveling_salesman.draw');
