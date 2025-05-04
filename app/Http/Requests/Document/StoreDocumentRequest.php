<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
                'trouve' => 'required|boolean',
                'sauvegarde' => 'required|boolean',
                'signale' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'type_document_id.exists' => 'Id de type de document introuvable',
            'type_document_id.required' => 'Id de type de document requis',
            'fichier_document.file' => 'Veuillez entrer un fichier',
            'fichier_document.required' => 'Le fichier du document est requis',
            'fichier_document.max' => 'Le fichier du document ne doit pas depasser 20Mo',
            'trouve.boolean' => 'Le champ trouve doit être un booléen',
            'trouve.required' => 'Le champ trouve est requis',
            'sauvegarde.boolean' => 'Le champ sauvegarde doit être un booléen',
            'sauvegarde.required' => 'Le champ sauvegarde est requis',
            'signale.boolean' => 'Le champ signale doit être un booléen',
            'signale.required' => 'Le champ signale est requis',
        ];
    }
}
 