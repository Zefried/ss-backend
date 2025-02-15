<?php

use App\Http\Controllers\Admin\DoctorController\DoctorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



// Web route for the form
// Route::get('/form/{step}', [DoctorController::class, 'showForm'])->name('form.step');

// Web route for form submission
Route::post('/form/submit', [DoctorController::class, 'testDoctor'])->name('form.submit');

// Extra Routes

Route::view('/','index')->name('index');
Route::view('about','about')->name('about');
Route::view('contact','contact')->name('contact');
Route::view('doctor-worker-registration','doctor_worker')->name('doctor-registration');
// Route::view('pharma-registration','pharma-registration')->name('pharma-registration');
Route::view('services','services')->name('services');
Route::view('about-sec','section.about-sec')->name('section.about-sec');
Route::view('contact-sec','section.contact-sec')->name('section.contact-sec');
Route::view('counter-sec','section.counter-sec')->name('section.counter-sec');
Route::view('mission-vision-sec','section.mission-vision-sec')->name('section.mission-vision-sec');
Route::view('service-sec','section.service-sec')->name('section.service-sec');
Route::view('why-choose-sec','section.why-choose-sec')->name('section.why-choose-sec');