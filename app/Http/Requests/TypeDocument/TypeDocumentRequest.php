<?php

namespace App\Http\Requests\TypeDocument;

use Illuminate\Foundation\Http\FormRequest;

class TypeDocumentRequest extends FormRequest
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
            'libelle' => 'nullable|string|max:1000',
            'frais' => 'required|numeric|min:0',
            'recompense' => 'required|numeric|min:0',
            'validite' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est requis.',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'libelle.string' => 'Le libellé doit être une chaîne de caractères.',
            'libelle.max' => 'Le libellé ne doit pas dépasser 1000 caractères.',
            'frais.required' => 'Les frais sont requis.',
            'frais.numeric' => 'Les frais doivent être un nombre.',
            'frais.min' => 'Les frais doivent être supérieurs ou égaux à 0.',
            'recompense.required' => 'La récompense est requise.',
            'recompense.numeric' => 'La récompense doit être un nombre.',
            'recompense.min' => 'La récompense doit être supérieure ou égale à 0.',
            'validite.required' => 'La validité est requise.',
            'validite.boolean' => 'La validité doit être vrai ou faux.',
        ];
    }
}
