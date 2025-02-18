<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SpeakerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// CRUD DE LOS EVENTOS API  
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::patch('/events/{id}', [EventController::class, 'updatePartial']);
Route::delete('/events/{id}', [EventController::class, 'delete']);

// CRUD DE LOS PONENTES API
Route::get('/speakers', [SpeakerController::class, 'index']);
Route::get('/speakers/{id}', [SpeakerController::class, 'show']);
Route::post('/speakers', [SpeakerController::class, 'store']);
Route::put('/speakers/{id}', [SpeakerController::class, 'update']);
Route::patch('/speakers/{id}', [SpeakerController::class, 'updatePartial']);
Route::delete('/speakers/{id}', [SpeakerController::class, 'delete']);

// CRUD DE Los HORARIOS API
Route::get('/schedule', [ScheduleController::class, 'index']);
Route::get('/available-schedules', [ScheduleController::class, 'getAvailableSchedules']);
Route::get('/schedule/{id}', [ScheduleController::class, 'show']);
Route::post('/schedule', [ScheduleController::class, 'store']);
Route::put('/schedule/{id}', [ScheduleController::class, 'update']);
Route::patch('/schedule/{id}', [ScheduleController::class, 'updatePartial']);
Route::delete('/schedule/{id}', [ScheduleController::class, 'delete']);

// CRUD DE LAS INSCRIPCIONES
Route::get('/registrations', [RegistrationController::class, 'index']);
Route::get('/registrations/{id}', [RegistrationController::class, 'show']);
Route::post('/registrations', [RegistrationController::class, 'store']);
Route::put('/registrations/{id}', [RegistrationController::class, 'update']);
Route::patch('/registrations/{id}', [RegistrationController::class, 'updatePartial']);
Route::delete('/registrations/{id}', [RegistrationController::class, 'delete']);

// CRUD DE LOS PAGOS
Route::get('/payments', [PaymentController::class, 'index']);
Route::get('/payments/{id}', [PaymentController::class, 'show']);
Route::post('/payments', [PaymentController::class, 'store']);
Route::put('/payments/{id}', [PaymentController::class, 'update']);
Route::patch('/payments/{id}', [PaymentController::class, 'updatePartial']);
Route::delete('/payments/{id}', [PaymentController::class, 'delete']);