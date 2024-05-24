<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/clubs', [ClubController::class, "getClubsList"]);
Route::get('/club/{id}', [ClubController::class, "getClubDetail"]);

Route::get('/subscriptions', [SubscriptionController::class, "getSubscriptionList"]);
Route::get('/subscription', [SubscriptionController::class, "getSubscription"]);
Route::post('/buy-subscription', [SubscriptionController::class, "boundSubscription"]);

Route::get('/services', [ServiceController::class, "getServicesList"]);
Route::post('/service/{id}/register', [ServiceController::class, "makeAppointment"]);
Route::put('/service/{id}/register', [ServiceController::class, "changeAppointment"]);
Route::delete('/service/{id}/unregister', [ServiceController::class, "deleteAppointment"]);

Route::get('/client/{id}/registrations', [ServiceController::class, "getRegistrationsList"]);

Route::get('/service/{id}/dates', [ServiceController::class, "getAvailableDates"]);
Route::get('/service/{id}/date/{date}/timeslots', [ServiceController::class, "getAvailableTimeslots"]);

Route::post('/login', [Auth::class, "login"]);
Route::post('/register', [Auth::class, "register"]);

