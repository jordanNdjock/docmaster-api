<?php

namespace App\Http\Requests\Utilisateur;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
            'nom_utilisateur' => 'required|string|max:255|exists:users,nom_utilisateur',
            'tel' => [ 'required', 'string', 'regex:/^\+[1-9]\d{6,14}$/' ],
            'date_naissance' => 'required|date',
            'localisation' => 'nullable|string',
            'infos_paiement' => 'nullable|string',
            'photo_url' => [
                'nullable',
                $this->hasFile('photo_url') ? 'file' : 'string',
                Rule::when($this->hasFile('photo_url'), ['mimes:jpg,jpeg,png,gif', 'max:5120'])
            ],
            'solde' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nom_famille.required' => 'Le nom de famille est requis.',
            'prenom.required' => 'Le prénom est requis.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.exists' => 'Cette adresse e-mail n\'existe pas.',
            'nom_utilisateur.required' => 'Le nom d\'utilisateur est requis.',
            'tel.required' => 'Le numéro de téléphone est requis.',
            'date_naissance.required' => 'La date de naissance est requise.',
            'photo_url.file' => 'La photo de profil doit être une image de type JPG, PNG ou GIF',
            'photo_url.max' => 'La taille du fichier de profil ne doit pas dépasser 5Mo',
            'tel.regex' => 'Le numéro de téléphone doit être au format international.',
            'tel' => 'Le numéro de téléphone est requis',
        ];
    }
}
