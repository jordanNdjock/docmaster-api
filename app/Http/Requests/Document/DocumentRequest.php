<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
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
                'type_document_id' => 'required|string|exists:types_documents, id',
                'fichier_document' => 'required|file|mimes:jpg,png,pdf|max:20480',
                'nom_proprietaire' => 'required|string',
                'titre_document' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'type_document_id.exists' => 'Id du type de document introuvable',
            'type_document_id.required' => 'Id du type de document requis',
            'titre_document' => 'Titre du document requis',
            'fichier_document.file' => 'Veuillez entrer un fichier',
            'fichier_document.required' => 'Le fichier du document est requis',
            'fichier_document.max' => 'La taille du fichier du document ne doit pas depasser 20Mo',
            'nom_proprietaire.required' => 'Le nom du propriétaire est requis',
        ];
    }
}
 