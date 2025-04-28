<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementsTable extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transaction_id');
            $table->uuid('docmaster_id');
            $table->boolean('etat')->default(false);
            $table->boolean('supprime')->default(false);
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('docmaster_id')->references('id')->on('docmasters');
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
}

