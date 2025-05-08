<?php

// database/migrations/2025_04_28_000004_create_docmasters_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateDocmastersTable extends Migration
{
    public function up()
    {
        Schema::create('docmasters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('doc_chercheur_id')->nullable();
            $table->uuid('doc_trouveur_id')->nullable();
            $table->uuid('document_id');
            $table->integer('nombre_notif')->default(0);
            $table->enum('type_docmaster', ['Chercher', 'Trouver']);
            $table->enum('etat_docmaster', ['Récupération', 'Perdu', 'Retrouvé', 'Récupéré']);
            $table->date('date_action')->nullable();
            $table->decimal('credit', 8, 0)->default(0);
            $table->decimal('debit', 8, 0)->default(0);
            $table->boolean('confirm')->default(false);
            $table->string('code_confirm', 6)->nullable()->unique();
            $table->boolean('supprime')->default(false);
            $table->timestamps();

            $table->foreign('doc_chercheur_id')->references('id')->on('users');
            $table->foreign('doc_trouveur_id')->references('id')->on('users');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }

    public function down()
    {
        Schema::dropIfExists('docmasters');
    }
}

