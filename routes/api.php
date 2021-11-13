<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('loans', LoanController::class)->middleware('auth.basic');
Route::get('/loans/{loan}/repay', [
    "uses" => 'App\Http\Controllers\LoanController@repay',
    "as" => 'loans.repay'
])->middleware('auth.basic');