<?php

namespace App\Services;

use App\Models\Retrait;
use App\Models\Transaction;
use Illuminate\Pagination\Paginator;

class WithdrawalServices{
 
    public function __construct(
       protected TransactionServices $transactionServices
    ){}

    public function getAllUserWithdrawals(int $perPage = 10, ?int $page = null): array{
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Retrait::users(auth()->id())->
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

    public function getAllWithdrawals(int $perPage = 10, ?int $page = null): array{
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Retrait::
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

    public function makeWithdrawal(array $data): ?Transaction
    {
        $user = auth()->user();
        $verifMontant = $data['montant'] <= $user->solde;

        if(!$verifMontant)
            throw new \Exception("Solde insuffisant pour effectuer le retrait, Veuillez entrer un montant inférieur ou égal au solde : $user->solde !");

        $transaction = $this->transactionServices->initiateWithdrawal($data);

        return $transaction;
    }
}
