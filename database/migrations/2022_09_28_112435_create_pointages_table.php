<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pointages', function (Blueprint $table) {
$table->id();
$table->integer('id_employee') ;
$table->datetime('date_debut');
$table->datetime('date_debut_pause'); 
$table->datetime('date_fin_pause'); 
$table->datetime('date_fin'); 
$table->string('NB_heures'); 
$table->string('NB_heures_supplementaires'); 
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
        Schema::dropIfExists('pointages');
    }
}
