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
            'nom_utilisateur' => 'required|string|max:50|unique:users,nom_utilisateur',
            'email'    => 'required|email|max:255|unique:users,email',
            'mdp' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'nom_utilisateur.required' => 'Le nom d’utilisateur est requis.',
            'email.required'    => 'L\'adresse email est requise.',
            'email.unique'      => 'Cette adresse email est déjà utilisée.',
            'mdp.required' => 'Le mot de passe est requis.',
            'mdp.min'      => 'Le mot de passe doit comporter au moins 8 caractères.',
            'mdp.confirmed'=> 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
