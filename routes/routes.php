<?php

use Illuminate\Support\Facades\Route;
use S25\RatesApiLaravel\Controllers\ListenRateChangeController;

Route::post('/listen-rate-change', ListenRateChangeController::class);