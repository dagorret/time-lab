<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogController;

Route::get('/ti-table', [LogController::class, 'index']);
