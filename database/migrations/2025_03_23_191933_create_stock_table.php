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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->string('sous_categorie')->unique();
            $table->integer('quantite')->default(0);
            $table->integer('seuil_min')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('stock');
    }
};
