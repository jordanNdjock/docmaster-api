<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampsTable extends Migration
{
    public function up()
    {
        Schema::create('champs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('nature_id');
            $table->string('libelle');
            $table->boolean('number')->default(false);
            $table->integer('position')->default(0);
            $table->boolean('supprime')->default(false);
            $table->timestamps();

            $table->foreign('nature_id')->references('id')->on('natures');
        });
    }

    public function down()
    {
        Schema::dropIfExists('champs');
    }
}
