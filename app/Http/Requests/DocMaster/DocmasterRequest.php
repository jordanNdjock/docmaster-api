<?php

namespace App\Http\Requests\Docmaster;

use App\Http\Requests\Document\DocumentRequest;
use Illuminate\Foundation\Http\FormRequest;

class DocmasterRequest extends FormRequest
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
        $rules = [
            'type_docmaster' => 'required|in:Chercher,Trouver',
            'date_action' => 'nullable|date',
            'document_id' => 'nullable|string|exists:documents,id',
        ];

        if (! $this->filled('document_id')) {
            $createDocReq = app(DocumentRequest::class);
            $rules = array_merge(
                $rules,
                $createDocReq->rules()
            );
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'type_docmaster.required' => 'Le type de docmaster est requis',
            'type_docmaster.in' => 'Le type de docmaster doit être Chercher ou Trouver',
            'document_id.exists' => 'Id du document introuvable',
            'document_id.string' => 'Id du document doit être une chaîne de caractères',
        ];
        if (! $this->filled('document_id')) {
            $createDocReq = app(DocumentRequest::class);
            $messages = array_merge(
                $messages,
                $createDocReq->messages()
            );
        }
        return $messages;
    }

}
