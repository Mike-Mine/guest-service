<?php

use Illuminate\Support\Facades\Route;

Route::get('/version', function () {
    return response()->json([
        'Laravel' => app()->version(),
        'PHP' => phpversion(),
    ], 200);
})->middleware('api.token');
