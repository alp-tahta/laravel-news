<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('news', 'App\Http\Controllers\NewsController');
Route::get('news-search', [App\Http\Controllers\NewsController::class, 'search']);
