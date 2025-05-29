<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InterFace\FlightsController;
use App\Http\Controllers\Site\AutoCompleteController;
use App\Http\Controllers\Site\FlightTicketController;
use App\Http\Controllers\Site\IndexController;
use App\Http\Controllers\Site\PaymentController;
use App\Http\Controllers\SmsController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/',[IndexController::class,'index'])->name('index');
Route::get('/login',[AuthController::class,'loginShow'])->name('login.show');
Route::get('/register',[AuthController::class,'registerShow'])->name('register.show');
Route::post('/register',[AuthController::class,'register'])->name('register');

Route::get('/flights',[FlightTicketController::class,'flightsTickets'])->name('flights.tickets');

Route::get('/tickets',[IndexController::class,'tickets'])->name('tickets');