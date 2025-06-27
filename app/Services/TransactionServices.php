<?php

namespace App\Services;

use App\Models\AbonnementUser;
use App\Models\Docmaster;
use App\Models\Paiement;
use App\Models\Retrait;
use App\Models\Transaction;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TransactionServices
{
    public function getAllTransactions(int $perPage = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Transaction::
        paginate(
            $perPage, 
            ['*'],
            'page',
            $page
        );
        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ];
    }

    public function getAllUserTransactions(int $perPage = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Transaction::with('user')->
        paginate(
            $perPage, 
            ['*'],
            'page',
            $page
        );
        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ];
    }

    // fonction d'initiation de transaction de paiement
    public function initiateTransaction(array $transactionData, string $id): Transaction
    {
        return DB::transaction(function () use ($transactionData, $id){
            $user = auth()->user();
            //requête vers l'api de nokash pour le paiement et initier la transaction
            $response = Http::withQueryParameters([
                'i_space_key' => env('NOKASH_I_SPACE_KEY'),
                'app_space_key' => env('NOKASH_APP_SPACE_KEY'),
                'phonenumber' => $transactionData['tel'],
                'amount' => $transactionData['montant'],
                'order_id' => $id,
                'payment_method' => $transactionData['payment_method'],
                'country' => 'CM',
            ])->post('https://api.nokash.app/lapas-on-trans/trans/301/api-payin-request');

            //en cas de reussite on procède à l'extraction des infos utiles
            if($response->successful()){
                //extraction de l'id de la transaction et du statut
                $data = $response->json();
                $trans_id = $data["data"]["id"];
                $status = $data["data"]["status"];

                    //recupération du type de transaction
                    if($transactionData['transactionable_type'] === 'docmaster'){
                        $transactionType = Docmaster::findOrFail($id);
                    }else if($transactionData["transactionable_type"] === 'abonnement'){
                        $transactionType = AbonnementUser::findOrFail($id);
                    }

                    // Création de la transaction et récupération du modèle pour avoir l'id plutard
                    $transaction = $transactionType->transactions()->create([
                        "user_id" => $user->id,
                        "statut" => $status,
                        "identifiant" => $trans_id,
                        "montant" => $transactionData['montant']
                    ]);

                    // Contrôle du statut de la transaction pour vérifier si le paiement a été effectué ou pas
                    $resPayment = $this->checkTransactionStatus($trans_id);
                    
                    // Vérifie que le statut est bien SUCCESS
                    if ($resPayment && $resPayment['data'] === 'SUCCESS') {
                        
                        // Modification du statut de la transaction en SUCCESS
                        $transaction->update([
                            'statut' => "SUCCESS"
                        ]);
                        
                        //Création du paiement
                        Paiement::create([
                            'transaction_id' => $transaction->id,
                            'etat' => true,
                            'montant' => $transactionData['montant']
                        ]);
                        
                        // Mise à jour de l'abonnement ou du docmaster car réussie
                        if($transaction->transactionable_type === 'docmaster'){
                            //
                            $transactionType->update([
                                'credit' => (int) $transaction->montant/2,
                                'debit' => (int) $transaction->montant/2,
                                'etat_docmaster' => 'Récupération'
                            ]);
                        } else if($transaction->transactionable_type === 'abonnement'){
                            $transactionType->update([
                                'actif' => true,
                                'date_debut' => now(),
                                'date_expiration' => now()->addYear()
                            ]);
                        }

                        //Log pour la transaction réussie
                        Log::channel('user_actions')->info('Transaction réussie ', [
                            'id'           => $transaction->id,
                            'type_transaction' => $transaction->transactionable_type,
                            'montant'        => $transaction->montant,
                            'initiated_by'   => $user ? $user->email : 'unknown',
                        ]);  
                    } else {

                        //Modification du statut de la transaction en PENDING ou FAILED
                        $transaction->update([
                            'statut' => $resPayment['data'] ?? "FAILED"
                        ]);

                        //Log pour la transaction échouée
                        Log::channel('user_actions')->info('Transaction échouée', [
                            'id'           => $transaction->id,
                            'type_transaction' => $transaction->transactionable_type,
                            'montant'        => $transaction->montant,
                            'initiated_by'   => $user ? $user->email : 'unknown',
                        ]); 
                    }
                    // on retourne la transaction que ce soit failed ou success
                    return $transaction;
            }else{
                throw new \Exception("Échec de l'appel API avec code HTTP : ".$response->body() . $response->status());
            }
        });
    }


    // Fonction de contrôle de statut avec compte à rebours
    private function checkTransactionStatus(string $trans_id, int $attempts = 10, float $initialDelay = 0.5, float $maxDelay = 8): ?array
    {
        set_time_limit(120);
        $delay = $initialDelay;

        for ($i = 0; $i < $attempts; $i++) {
            $response = Http::withQueryParameters([
                'transaction_id' => $trans_id
            ])->post('https://api.nokash.app/lapas-on-trans/trans/301/status-request');

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['data']['status'] ?? null;

                if ($status === 'SUCCESS' || $status === 'FAILED') {
                    return $data;
                }
            }

            if ($i < $attempts - 1) {
                usleep((int) ($delay * 1_000_000));
                $delay = min($delay * 2, $maxDelay);
            }
        }

        return $data ?? null;
    }

    // fonction d'initiation de retrait
    public function initiateWithdrawal(array $withdawalData){
        return DB::transaction( function() use ($withdawalData){
            $user = auth()->user();
            $authcode = $this->generateAuthcode();

            // savoir si le authcode est retourné
            if($authcode != null){
                $response = Http::withHeaders([
                    "auth-code" => $authcode,
                ])->asJson()->post('https://api.nokash.app/lapas-on-trans/trans/api-payout-request/407', [
                     'payment_type' => "CM_MOBILEMONEY",
                     'country' => "CM",
                     'i_space_key' => env('NOKASH_I_SPACE_KEY'),
                     'app_space_key' => env('NOKASH_APP_SPACE_KEY'),
                     'user_data' => [
                        'user_phone'=> $withdawalData['tel'] 
                     ],
                     'amount' => $withdawalData['montant'],
                     'payment_method' => $withdawalData['payment_method'],
                     'order_id' => Str::random(10)
                ]);

                if($response->successful()){
                    $data = $response->json();
                    $trans_id = $data["data"]["id"];
                    $status = $data["data"]["status"];

                    $retrait = Retrait::create([
                        "user_id" => $user->id,
                        "montant" => $withdawalData['montant'],
                        "tel" => $withdawalData['tel'],
                        "date" => now(),
                    ]);

                    $transaction = $retrait->transactions()->create([
                        "user_id" => $user->id,
                        "statut" => $status,
                        "identifiant" => $trans_id,
                        "montant" => $withdawalData['montant']
                    ]);

                    $resPayment = $this->checkTransactionStatus($trans_id);

                    if ($resPayment && $resPayment['data'] === 'SUCCESS') {
                        $transaction->update([
                            'statut' => "SUCCESS"
                        ]);

                        $retrait->update([
                            'etat' => true
                        ]);

                        $user->update([
                            'solde' => $user->solde - $withdawalData['montant']
                        ]);

                        Log::channel('user_actions')->info('Transaction réussie ', [
                            'id'           => $transaction->id,
                            'type_transaction' => $transaction->transactionable_type,
                            'montant'        => $transaction->montant,
                            'initiated_by'   => $user ? $user->email : 'unknown',
                        ]);  
                    }else{
                        //Modification du statut de la transaction en PENDING ou FAILED
                        $transaction->update([
                            'statut' => $resPayment['data'] ?? "FAILED"
                        ]);

                        //Log pour la transaction échouée
                        Log::channel('user_actions')->info('Transaction échouée', [
                            'id'           => $transaction->id,
                            'type_transaction' => $transaction->transactionable_type,
                            'montant'        => $transaction->montant,
                            'initiated_by'   => $user ? $user->email : 'unknown',
                        ]); 
                    }
                }
                else
                {
                    Log::channel('user_actions')->info('Échec de l\'appel API avec code HTTP : '.$response->body() . $response->status(), $response->headers());
                    throw new \Exception("Échec de l'appel API avec code HTTP : ".$response->body() . $response->status());
                }
                return $transaction;
            }
        });
    }

    // fonction pour générer le auth-code du retrait
    private function generateAuthcode(): string {
        $response = Http::withQueryParameters([
            'i_space_key' => env('NOKASH_I_SPACE_KEY'),
            'app_space_key' => env('NOKASH_APP_SPACE_KEY'),
        ])->post('https://api.nokash.app/lapas-on-trans/trans/auth');

        if($response->successful()){
                $data = $response->json();
                $auth_code = $data["data"];
                return $auth_code;
         }
        else
        {
            throw new \Exception("Échec de l'appel API avec code HTTP : ".$response->body() . $response->status());
        }
    }
}