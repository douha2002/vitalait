<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('equipements', function (Blueprint $table) {
            $table->string('article')->nullable()->change();
            $table->date('date_acquisition')->nullable()->change();
            $table->date('date_de_mise_en_oeuvre')->nullable()->change();
            $table->string('categorie')->nullable()->change();
            $table->string('sous_categorie')->nullable()->change();
            $table->string('matricule')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipements', function (Blueprint $table) {
            $table->string('article')->nullable(false)->change();
            $table->date('date_acquisition')->nullable(false)->change();
            $table->date('date_de_mise_en_oeuvre')->nullable(false)->change();
            $table->string('categorie')->nullable(false)->change();
            $table->string('sous_categorie')->nullable(false)->change();
            $table->string('matricule')->nullable(false)->change();
        });
    }
};
