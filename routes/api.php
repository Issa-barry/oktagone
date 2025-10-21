<?php

use Illuminate\Support\Facades\Route;
    use Illuminate\Http\Request;


use App\Http\Controllers\Auth\PublicRegisterController;
 use App\Http\Controllers\Auth\VerifyEmailController;


Route::post('/auth/register', [PublicRegisterController::class, 'store'])
    ->middleware('throttle:register');


Route::post('/auth/email/resend', function (Request $request) {
    $user = \App\Models\User::where('email', $request->input('email'))->firstOrFail();
    $user->sendEmailVerificationNotification();
    return response()->json(['message' => 'Email de vérification renvoyé.']);
});



Route::get('/auth/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed','throttle:6,1'])
    ->name('verification.verify'); // IMPORTANT: même nom que celui utilisé dans l'email

 