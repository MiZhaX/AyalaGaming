<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if(Auth::user()->is_student === null && Auth::user()->email !== "mbonelortiz@gmail.com") {
        return redirect()->route('complete-registration');
    } else {
        return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/events', function () {
    return view('events.events');
})->middleware(['auth', 'verified'])->name('events');

Route::middleware(['auth'])->group(function () {
    Route::get('/adminEvents', function () {
        if (Auth::user() && Auth::user()->email === 'mbonelortiz@gmail.com') {
            return view('events.adminEvents');
        }
        return redirect()->route('dashboard');
    })->name('adminEvents');
}); 

Route::get('/speakers', function () {
    return view('speakers.speakers');
})->middleware(['auth', 'verified'])->name('speakers');

Route::middleware(['auth'])->group(function () {
    Route::get('/adminSpeakers', function () {
        if (Auth::user() && Auth::user()->email === 'mbonelortiz@gmail.com') {
            return view('speakers.adminSpeakers');
        }
        return redirect()->route('dashboard');
    })->name('adminSpeakers');
}); 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/complete-registration', [UserController::class, 'showCompleteRegistration'])->name('complete-registration');
    Route::post('/complete-registration', [UserController::class, 'completeIsStudent'])->name('complete-isStudent.post');
});

Route::get('/complete-events', function () {
    return view('auth.complete-events');
})->middleware(['auth', 'verified'])->name('complete-events');

Route::post('/complete-payment', [UserController::class, 'showCompletePayment'])->middleware(['auth', 'verified'])->name('complete-payment');

Route::get('/my-registrations', function () {
    return view('registrations.my-registrations');
})->middleware(['auth', 'verified'])->name('my-registrations');

Route::get('/registrations', function () {
    return view('registrations.allRegistrations');
})->middleware(['auth', 'verified'])->name('registrations');

Route::get('/payments', function () {
    return view('payments.allPayments');
})->middleware(['auth', 'verified'])->name('payments');

require __DIR__.'/auth.php';
