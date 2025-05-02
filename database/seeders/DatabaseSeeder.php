<?php

namespace Database\Seeders;

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
