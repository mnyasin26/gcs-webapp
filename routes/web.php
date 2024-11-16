<?php

use App\Events\TelemetryUpdate;
use App\Http\Controllers\AppController;
use App\Http\Controllers\CenterPointController;
use App\Http\Controllers\FlightCodeController;
use App\Http\Controllers\GardenProfileController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TelemetriLogController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/map');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/', [MapController::class,'index'], function () {
    return view('map.index');
})->middleware(['auth', 'verified'])->name('map.index');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/center-point/data',[CenterPointController::class,'data'])->name('center-point.data');
    Route::resource('/center-point', CenterPointController::class);

    // Route::get('/drone-point/data',[CenterPointController::class,'data'])->name('drone-point.data');
    // Route::resource('/drone-point', CenterPointController::class);

    Route::get('/map',[MapController::class,'index'])->name('map.index');

    Route::get('/telemetri-log/data',[TelemetriLogController::class,'data'])->name('telemetri-log.data');
    Route::get('/telemetri-log/data/{flight_code}',[TelemetriLogController::class,'data_select'])->name('telemetri-log.data');

    Route::get('/garden-profile',[GardenProfileController::class,'index'])->name('garden-profile.index');

    Route::get('/flight-code/data',[FlightCodeController::class,'data'])->name('flight-code.data');
    Route::post('/flight-code/{flight_code}/select',[FlightCodeController::class,'select'])->name('flight-code.select');
    Route::post('/flight-code/{flight_code_id}/select-view',[FlightCodeController::class,'select_view'])->name('flight-code.select-view');
    Route::resource('/flight-code', FlightCodeController::class);

    Route::prefix('setting')->group(function () {
        Route::get('/', [AppController::class, 'index'])->name('setting.index');
        Route::patch('/update', [AppController::class, 'update'])->name('setting.update');
    });
});

require __DIR__.'/auth.php';
