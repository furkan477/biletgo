<?php

use App\Http\Controllers\Site\AutoCompleteController;
use App\Http\Controllers\Site\FlightTicketController;
use App\Http\Controllers\Site\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/flight-ticket',[AutoCompleteController::class,'flightTicket'])->name('flight.ticket');
Route::get('/flight-search',[FlightTicketController::class,'flightSearch'])->name('flight.search');
Route::get('/flight-ticket-select',[FlightTicketController::class,'flightTicketSelect'])->name('flight.ticket.select');


Route::post('/payment-cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');