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
        Schema::create('abonnements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id');
            $table->string('type_document_id');
            $table->integer('nombre_documents');
            $table->date('date_expiration');
            $table->double('montant');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type_document_id')->references('id')->on('types_documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
