<?php

// database/migrations/2025_04_28_000003_create_documents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('type_document_id');
            $table->uuid('user_id');
            $table->text('contenu');
            $table->boolean('trouve')->default(false);
            $table->boolean('sauvegarde')->default(false);
            $table->boolean('signale')->default(false);
            $table->boolean('supprime')->default(false);
            $table->timestamps();

            $table->foreign('type_document_id')->references('id')->on('types_documents');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
