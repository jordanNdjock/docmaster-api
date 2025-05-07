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
        Schema::create('abonnement_users', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('abonnement_id');
            $table->boolean('actif')->default(false);
            $table->date('date_debut')->nullable();
            $table->date('date_expiration')->nullable();
            $table->timestamps();

            $table->foreign('abonnement_id')->references('id')->on('abonnements');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnement_type_documents');
    }
};
