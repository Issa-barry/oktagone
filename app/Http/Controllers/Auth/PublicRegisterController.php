<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use App\Traits\JsonResponseTrait;
use Illuminate\Database\QueryException;
use Throwable;

class PublicRegisterController extends Controller
{
    use JsonResponseTrait;

    public function store(PublicRegisterRequest $request)
    {
        try {
            $data = $request->validated();

            // Vérifie si l'e-mail existe déjà
            if (User::where('email', $data['email'])->exists()) {
                return $this->responseJson(false, 'Cet e-mail est déjà utilisé.', null, 409);
            }

            // Création du user
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Attribution du rôle par défaut
            Role::findOrCreate('oktagone.operator');
            $user->assignRole('oktagone.operator');

            // Envoi de l’e-mail de vérification
            event(new Registered($user));

            return $this->responseJson(true, 'Compte créé avec succès. Vérifie ton e-mail avant de te connecter.', $user, 201);
        } catch (\Throwable $e) {
            return $this->handleException($e); // <-- orthographe exacte
        }

    }
}
