<?php

use Illuminate\Support\Facades\Route;
    use Illuminate\Http\Request;


use App\Http\Controllers\Auth\PublicRegisterController;

Route::post('/auth/register', [PublicRegisterController::class, 'store'])
    ->middleware('throttle:register');


Route::post('/auth/email/resend', function (Request $request) {
    $user = \App\Models\User::where('email', $request->input('email'))->firstOrFail();
    $user->sendEmailVerificationNotification();
    return response()->json(['message' => 'Email de vérification renvoyé.']);
});
