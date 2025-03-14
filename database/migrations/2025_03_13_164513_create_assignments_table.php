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
            $table->unsignedBigInteger('employees_id'); // Must match employees table
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
    
            // Fix the foreign key constraint for equipements
            $table->foreign('numero_de_serie')
            ->references('numero_de_serie')->on('equipements')
            ->onDelete('cascade');
    
            // Fix the foreign key constraint for employees
            $table->foreign('employees_id')
                  ->references('id')->on('employees')
                  ->onDelete('cascade');

                 
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
