<?php

use App\Livewire\CustomersComponent;
use App\Livewire\QuotesComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/Cotizaciones', QuotesComponent::class)->name('quotes');
    Route::get('/Clientes', CustomersComponent::class)->name('customers');
});
