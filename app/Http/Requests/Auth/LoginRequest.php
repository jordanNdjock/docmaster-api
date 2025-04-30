<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom_utilisateur' => 'required|string|max:50|exists:users,nom_utilisateur',
            'email'    => 'required|email|max:255|exists:users,email',
            'mdp' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'nom_utilisateur.required' => 'Le nom d’utilisateur est requis.',
            'nom_utilisateur.string'   => 'Le nom d’utilisateur doit être une chaîne de caractères.',
            'nom_utilisateur.max'      => 'Le nom d’utilisateur ne doit pas dépasser 50 caractères.',
            'nom_utilisateur.exists'   => 'Ce nom d’utilisateur n\'existe pas dans notre base de données.',
            'email.required'    => 'L\'adresse email est requise.',
            'email.email'       => 'L\'adresse email doit être valide.',
            'email.max'         => 'L\'adresse email ne doit pas dépasser 255 caractères.',
            'email.exists'      => 'Cette adresse email n\'existe pas dans notre base de données.',
            'mdp.string'        => 'Le mot de passe doit être une chaîne de caractères.',
            'mdp.required'      => 'Le mot de passe est requis.',
            'mdp.min'           => 'Le mot de passe doit comporter au moins 8 caractères.',
        ];
    }
}
