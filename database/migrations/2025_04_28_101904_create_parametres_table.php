<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametresTable extends Migration
{
    public function up()
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('nature_id');
            $table->double('frais');
            $table->double('recompense');
            $table->integer('trouver')->default(0);
            $table->integer('rechercher')->default(0);
            $table->integer('remis')->default(0);
            $table->boolean('supprime')->default(false);
            $table->timestamps();

            $table->foreign('nature_id')->references('id')->on('natures');
        });
    }

    public function down()
    {
        Schema::dropIfExists('parametres');
    }
}

