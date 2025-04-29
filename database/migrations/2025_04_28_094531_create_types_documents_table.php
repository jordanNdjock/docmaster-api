<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('types_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titre');
            $table->string('libelle');
            $table->double('frais');
            $table->double('recompense');
            $table->boolean('validite')->default(false);
            $table->date('date_expiration')->nullable();
            $table->boolean('supprime')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('types_documents');
    }
}
