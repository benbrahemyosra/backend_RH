<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoprofessionnellesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infoprofessionnelles', function (Blueprint $table) {
            $table->id();
            $table->string('AdresseElectronique');
            $table->string('Adressepro');
            $table->string('telephonepro');
            $table->integer('typeemployee');
            $table->integer('poste');
            $table->string('codepointage');
            $table->string('codepin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infoprofessionnelles');
    }
}
