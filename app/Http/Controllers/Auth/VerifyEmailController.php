<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Vérifie l’empreinte de l’email (même logique que Laravel)
        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            return response()->json(['message' => 'Lien de vérification invalide.'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirectToFrontend('already');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return $this->redirectToFrontend('success');
    }

    protected function redirectToFrontend(string $status)
    {
        // URL de redirection Angular après vérif
        $url = env('FRONTEND_VERIFIED_REDIRECT', 'http://localhost:4200/auth/verified');
        return redirect()->away($url . '?status=' . $status);
    }
}
