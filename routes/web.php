<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-all-roles', function () {
    return response()->json([
        'message' => 'Web routes working'
    ]);
});
