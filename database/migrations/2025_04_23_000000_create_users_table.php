<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom_famille');
            $table->string('prenom');
            $table->string('initial_2_prenom')->nullable();
            $table->string('nom_utilisateur')->unique();
            $table->string('email')->unique();
            $table->string('mdp');
            $table->string('tel')->unique();
            $table->date('date_naissance')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('localisation')->nullable();
            $table->string('infos_paiement')->nullable();
            $table->decimal('solde', 8, 0)->default(0);
            $table->string('code_invitation', 8)->unique();
            $table->boolean('supprime')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
