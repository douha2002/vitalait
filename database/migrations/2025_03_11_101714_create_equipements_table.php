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
        Schema::create('equipements', function (Blueprint $table) {
            $table->string('numero_de_serie')->primary(); // Set as primary key
            $table->string('article');
            $table->integer('quantite');
            $table->date('date_acquisition');
            $table->date('date_de_mise_en_oeuvre');
            $table->string('categorie');
            $table->string('sous_categorie');
            $table->string('matricule');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};
