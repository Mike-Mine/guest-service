<?php

use App\Http\Controllers\Api\GuestController;
use Illuminate\Support\Facades\Route;

Route::get('/version', function () {
    return response()->json([
        'Laravel' => app()->version(),
        'PHP' => phpversion(),
    ], 200);
})->middleware('api.token');

Route::middleware('api.token')->group(function () {
    Route::apiResource('guests', GuestController::class);
});
