<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->integer('typeplanning')->nullable();
            $table->string('titre')->nullable();
            $table->string('description')->nullable();
            $table->date('datedebut')->nullable();
            $table->date('datefin')->nullable();
            $table->BOOLEAN('expand')->nullable();
            $table->json('liste_employees')->nullable();
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
        Schema::dropIfExists('plannings');
    }
}
