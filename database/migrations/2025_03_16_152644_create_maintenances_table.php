<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
{
    Schema::create('maintenances', function (Blueprint $table) {
        $table->id();
        $table->string('numero_de_serie'); // Foreign key to equipements table
        $table->foreign('numero_de_serie')->references('numero_de_serie')->on('equipements')->onDelete('cascade');
        $table->unsignedBigInteger('fournisseur_id')->nullable(); // Supplier ID
        $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('set null');
        $table->date('date_panne');
        $table->date('date_affectation')->nullable();
        $table->date('date_reception')->nullable();
        $table->text('commentaires')->nullable();
        $table->timestamps();
    });
}


    public function down() {
        Schema::dropIfExists('maintenances');
    }
};
