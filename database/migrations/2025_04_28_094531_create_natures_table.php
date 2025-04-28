<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNaturesTable extends Migration
{
    public function up()
    {
        Schema::create('natures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('libelle');
            $table->boolean('supprime')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('natures');
    }
}
