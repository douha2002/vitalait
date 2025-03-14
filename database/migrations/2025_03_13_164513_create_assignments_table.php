<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('numero_de_serie'); // Must match equipements table
            $table->string('employees_id'); // Must match employees table
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
    
            $table->foreign('numero_de_serie')->references('numero_de_serie')->on('equipements');
            $table->foreign('employees_id')->references('matricule')->on('employees');

                 
        });
    }
    
        
      
   

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};
