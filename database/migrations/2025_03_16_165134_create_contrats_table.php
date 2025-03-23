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
    Schema::create('contrats', function (Blueprint $table) {
        $table->id();
        $table->string('numero_de_serie');
        $table->foreign('numero_de_serie')->references('numero_de_serie')->on('equipements')->onDelete('cascade');
        $table->unsignedBigInteger('fournisseur_id')->nullable();
        $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('set null');
        $table->date('date_debut');
        $table->date('date_fin');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
