<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            "montant" => "required|numeric|min:0",
            "tel" => [ 'required', 'string', 'regex:/^\d{6,14}$/' ],
            "payment_method" => "required|string|in:ORANGE_MONEY,MTN_MOMO",
            "transactionable_type" => "required|string|in:docmaster,abonnement",
        ];
    }

    public function messages(): array
    {
        return [
            "montant.required" => "Le montant est requis.",
            "montant.numeric" => "Le montant doit être un nombre.",
            "montant.min" => "Le montant doit être supérieur ou égal à 0.",
            "tel.required" => "Le numéro de téléphone est requis.",
            "tel.regex" => "Le numéro de téléphone doit être au format international.",
            "order_id.max" => "L'identifiant de la commande ne peut pas dépasser 255 caractères.",
            "payment_method.required" => "La méthode de paiement est requise.",
            "payment_method.string" => "La méthode de paiement doit être une chaîne de caractères.",
            "payment_method.in" => "La méthode de paiement doit être ORANGE_MONEY ou MTN_MOMO.",
            "transactionable_type.required" => "Le type de transaction est requis",
            "transactionable_type.in" => "Le type de transaction doit être docmaster ou abonnement",
        ];
    }
}
