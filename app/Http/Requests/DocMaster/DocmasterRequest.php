<?php

namespace App\Http\Requests\Docmaster;

use App\Http\Requests\Document\DocumentRequest;
use App\Models\TypeDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocmasterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'type_docmaster'   => 'required|in:Chercher,Trouver',
            'date_action'      => 'nullable|date',
        ];

        $mode = $this->input('type_docmaster');
        $docReq     = app(DocumentRequest::class);
        $documentRules = $docReq->rules();

        if ($mode === 'Chercher') {
            $rules = array_merge($rules, [
                'type_document_id' => $documentRules['type_document_id'] ?? 'required|string|exists:type_documents,id',
                'nom_proprietaire' => $documentRules['nom_proprietaire'] ?? 'required|string',
                'titre_document'   => $documentRules['titre_document'] ?? 'required|string',
            ]);
        }

        elseif ($mode === 'Trouver') {
            $rules = array_merge($rules, [
                'type_document_id' => $documentRules['type_document_id'] ?? 'required|string|exists:type_documents,id',
                'nom_proprietaire' => $documentRules['nom_proprietaire'] ?? 'required|string',
                'titre_document'   => $documentRules['titre_document'] ?? 'required|string',
                'date_expiration'  => $documentRules['date_expiration'],
                'nom_trouveur'     => 'required|string',
                'tel_trouveur'     => 'required|string|regex:/^[1-9]\d{6,14}$/',
                'infos_docs'       => 'nullable|string',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'type_docmaster.required'     => 'Le type de séclaration est requis.',
            'type_docmaster.in'           => 'Le type de déclaration doit être “Chercher” ou “Trouver”.',
            'type_document_id.exists'     => 'Le type de document sélectionné est introuvable.',
            'date_action.date'            => 'La date d\'action doit être une date valide.',
            'nom_proprietaire.required'   => 'Le nom du propriétaire est requis.',
            'titre_document.required'     => 'Le titre du document est requis.',
            'type_document_id.required' => 'Le type de document est requis.',
        ];

        $messagesTrouveur = [
            'date_expiration.required_if' => 'La date d’expiration est requise pour ce type de document.',
            'date_expiration.date' => 'La date d\'expiration doit être une date valide.',
            'tel_trouveur.regex'          => 'Le numéro du trouveur doit être au format international (+237690099878).',
            'tel_trouveur.required'       => 'Le numéro du trouveur est requis.',
            'nom_trouveur.required'       => 'Le nom du trouveur est requis.',
        ];

        if ($this->input('type_docmaster') === 'Trouveur') {
            $messages = array_merge($messages, $messagesTrouveur);
        }

        return $messages;
    }
}
