<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('prenom');
            $table->string('initial_2_prenom')->nullable();
            $table->string('nom_famille');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('tel')->nullable();
            $table->date('date_naissance')->nullable();
            $table->text('infos_paiement')->nullable();
            $table->string('code')->nullable();
            $table->boolean('supprime')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
