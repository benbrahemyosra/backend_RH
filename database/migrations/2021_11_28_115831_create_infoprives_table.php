<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoprivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infoprives', function (Blueprint $table) {
            $table->id();
            $table->string('adresseLieu');
            $table->string('telephone');
            $table->string('numBancaire');
            $table->string('genre');
            $table->string('etatcivil');
            $table->string('nationnalite');
            $table->string('numidentification');
            $table->string('numpasseport');
            $table->date('datenaissance');
            $table->string('lieunaissance');
            $table->string('paysnaissance');
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
        Schema::dropIfExists('infoprives');
    }
}
