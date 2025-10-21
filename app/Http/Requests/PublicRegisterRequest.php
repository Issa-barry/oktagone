<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicRegisterRequest extends FormRequest
{
   /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // true = tout le monde peut l’utiliser
        // false = bloqué par défaut
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * Règles de validation des données entrantes.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];
    }
}
