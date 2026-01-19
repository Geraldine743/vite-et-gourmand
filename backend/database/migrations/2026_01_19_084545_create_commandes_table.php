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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('statut_commande_id')->constrained();
            $table->string('numero_commande');
            $table->date('date_commande');
            $table->date('date_prestation');
            $table->date('date_livraison');
            $table->time('heure_livraison');
            $table->string('adresse_livraison');
            $table->string('ville_livraison');
            $table->string('code_postal_livraison');
            $table->integer('nombre_personnes')->unsigned();
            $table->decimal('prix_menu', 8, 2)->unsigned();
            $table->decimal('prix_livraison', 8, 2)->unsigned();
            $table->decimal('total_commande', 10, 2)->unsigned();
            $table->boolean('pret_materiel')->default(false);
            $table->boolean('retour_materiel')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comnmandes');
    }
};
