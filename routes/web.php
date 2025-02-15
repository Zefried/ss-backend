<?php

use App\Http\Controllers\Admin\DoctorController\DoctorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



// Web route for the form
Route::get('/form/{step}', [DoctorController::class, 'showForm'])->name('form.step');

// Web route for form submission
Route::post('/form/submit', [DoctorController::class, 'testDoctor'])->name('form.submit');