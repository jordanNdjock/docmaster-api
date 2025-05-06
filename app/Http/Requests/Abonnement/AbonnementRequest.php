<?php

namespace App\Http\Requests\Abonnement;

use Illuminate\Foundation\Http\FormRequest;

class AbonnementRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'nombre_docs_par_type' => 'required|integer|min:1',
            'date_debut' => 'required|date',
            'date_expiration' => 'required|date|after_or_equal:date_debut',
            'montant' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est requis.',
            'nombre_docs_par_type.required' => 'Le nombre de documents par type est requis.',
            'nombre_docs_par_type.integer' => 'Le nombre de documents par type doit être un entier.',
            'date_debut.required' => 'La date de début est requise.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_expiration.date' => 'La date d\'expiration doit être une date valide.',
            'date_expiration.required' => 'La date d\'expiration est requise.',
            'date_expiration.after_or_equal' => 'La date d\'expiration doit être égale ou postérieure à la date de début.',
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
        ];
    }
}
