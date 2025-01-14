<?php

use App\Http\Controllers\AccountRegister\AccountRegister;
use App\Http\Controllers\AuthController\AdminLogin;
use App\Http\Controllers\AuthController\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// OAuth callback route for future use, not currently used in the project, however setup is done.
Route::get('/auth/{provider}/callback', [AuthController::class, 'googleCallback']);
// Ends here


// User Authentication Routes 

        // Admin Authentication controller starts here 
        Route::post('/admin-register', [AccountRegister::class, 'adminRegister']);
        Route::post('/admin-login', [AdminLogin::class, 'adminLogin']);
        // Ends here
        
// Auth Routes Ends here



