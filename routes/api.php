<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use Illuminate\Support\Facades\Route;

Route::get('missing-orders', [APIController::class, 'missingOrder']);
Route::get('products', [APIController::class, 'products']);
