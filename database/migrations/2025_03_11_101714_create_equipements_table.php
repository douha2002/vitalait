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
            $table->string('article')->nullable();
            $table->integer('quantite')->nullable();
            $table->date('date_acquisition')->nullable();
            $table->date('date_de_mise_en_oeuvre')->nullable();
            $table->string('categorie')->nullable();
            $table->string('sous_categorie')->nullable();
            $table->string('matricule')->nullable();
            $table->timestamps();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
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
