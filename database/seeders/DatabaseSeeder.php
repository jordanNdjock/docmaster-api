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
        TypeDocument::factory(1)->create();

        Abonnement::factory(1)->create([
            'titre' => 'Basique (Individuel)',
            'nombre_docs_par_type' => 2,
            'montant' => 500,
            'supprime' => false,
        ]);

        Abonnement::factory(1)->create([
            'titre' => 'Standard',
            'nombre_docs_par_type' => 5,
            'montant' => 1000,
            'supprime' => false,
        ]);

        Abonnement::factory(1)->create([
            'titre' => 'Familial',
            'nombre_docs_par_type' => 10,
            'montant' => 2000,
            'supprime' => false,
        ]);

        Abonnement::factory(1)->create([
            'titre' => 'Entreprise',
            'nombre_docs_par_type' => 10000,
            'montant' => 5000,
            'supprime' => false,
        ]);

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
            'supprime' => false,
            'initial_2_prenom' => 'TU',
            'email_verified_at' => now(),
            'remember_token' => 'testtoken',
         ]);
        
        Admin::factory()->create([
            'nom_utilisateur' => 'Admin',
            'mdp' => 'adminpassword',
            'tel' => '+23790998778',
            'email' => 'admin@example.com',
            'supprime' => false,
        ]);
    }
}
