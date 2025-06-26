<?php

namespace Database\Seeders;

use App\Models\Abonnement;
use App\Models\Admin;
use App\Models\TypeDocument;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $definitionTypeDocument = [
            [
                'titre'       => "Carte d'identité",
                'libelle'     => 'CNI',
                'frais'       => 1000,
                'recompense'  => 500,
                'validite'    => true,
            ],
            [
                'titre'       => 'Passeport',
                'libelle'     => 'PST',
                'frais'       => 2000,
                'recompense'  => 1000,
                'validite'    => false,
            ],
            [
                'titre'       => 'Permis de conduire',
                'libelle'     => 'PRM',
                'frais'       => 1500,
                'recompense'  => 750,
                'validite'    => true,
            ],
            [
                'titre'       => 'Acte de naissance',
                'libelle'     => 'ATN',
                'frais'       => 800,
                'recompense'  => 100,
                'validite'    => false,
            ],
        ];

        TypeDocument::factory()
        ->count(count($definitionTypeDocument))
        ->sequence(...$definitionTypeDocument)
        ->create();

        $definitionAbonnement = 
        [
            [
                'titre' => 'Basique (Individuel)',
                'nombre_docs_par_type' => 2,
                'montant' => 500,
            ],
            [
                'titre' => 'Standard',
                'nombre_docs_par_type' => 5,
                'montant' => 1000,
            ],
            [
                'titre' => 'Familial',
                'nombre_docs_par_type' => 10,
                'montant' => 2000,
            ],
            [
                'titre' => 'Entreprise',
                'nombre_docs_par_type' => 10000,
                'montant' => 5000,
            ],

        ];
        Abonnement::factory()
        ->count(count($definitionAbonnement))
        ->sequence(...$definitionAbonnement)
        ->create();

        User::factory()->create([
            'nom_famille' => 'Test',
            'prenom' => 'Test User',
            'email' => 'test@example.com',
            'mdp' => 'password123',
            'nom_utilisateur' => 'testuser',
            'tel' => '1234567890',
            'date_naissance' => '2000-01-01',
            'infos_paiement' => 'Test payment info',
            'localisation' => 'Test location',
            'code_invitation' => 'TESTCODE',
            'initial_2_prenom' => 'TU',
            'email_verified_at' => now(),
            'remember_token' => 'testtoken',
         ]);
        
        Admin::factory()->create([
            'nom_utilisateur' => 'Admin',
            'mdp' => 'adminpassword',
            'tel' => '23790998778',
            'email' => 'admin@example.com',
        ]);
    }
}
