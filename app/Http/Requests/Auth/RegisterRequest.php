<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'nom_famille' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mdp' => 'required|string|min:8|confirmed',
            'nom_utilisateur' => 'required|string|max:255|unique:users,nom_utilisateur',
            'tel' => [ 'required', 'string', 'regex:/^[1-9]\d{6,14}$/' ],
            'date_naissance' => 'required|date',
            'localisation' => 'nullable|string',
            'infos_paiement' => 'nullable|string',
            'photo_url' => 'nullable|file|mimes:jpg,png,jpeg,gif|max:5120',
        ];
    }
    public function messages(): array
    {
        return [
            'nom_famille.required' => 'Le nom de famille est requis.',
            'prenom.required' => 'Le prénom est requis.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'mdp.required' => 'Le mot de passe est requis.',
            'mdp.min' => 'Le mot de passe doit comporter au moins 8 caractères.',
            'mdp.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'nom_utilisateur.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'nom_utilisateur.required' => 'Le nom d\'utilisateur est requis.',
            'tel.required' => 'Le numéro de téléphone est requis.',
            'tel.regex' => 'Le numéro de téléphone doit être au format international.',
            'date_naissance.required' => 'La date de naissance est requise.',
            'date_naissance.date' => 'La date de naissance doit être une date valide.',
            'date_naissance.date_format' => 'La date de naissance doit être au format YYYY-MM-DD.',
            'date_naissance.before' => 'La date de naissance doit être une date passée.',
            'photo_url.file' => 'La photo de profil doit être une image de type JPG, PNG ou GIF',
            'photo_url.max' => 'La photo de profil ne doit pas dépasser 5Mo'
        ];
    }
}
