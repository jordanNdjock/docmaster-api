<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom_utilisateur' => $this->nom_utilisateur,
            'initial_2_prenom' => $this->initial_2_prenom,
            'prenom' => $this->prenom,
            'nom_famille' => $this->nom_famille,
            'email' => $this->email,
            'password' => $this->password,
            'tel' => $this->tel,
            'date_naissance' => $this->date_naissance,
            'infos_paiement' => $this->infos_paiement,
            'code_invitation' => $this->code_invitation,
            'localisation' => $this->localisation,
            'supprime' => $this->supprime,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
