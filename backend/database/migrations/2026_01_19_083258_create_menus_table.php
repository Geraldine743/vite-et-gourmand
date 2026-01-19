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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regime_id')->constrained();
            $table->foreignId('theme_id')->constrained();
            $table->string('titre');
            $table->text('description');
            $table->integer('nb_personne')->unsigned();
            $table->decimal('prix_par_personne', 8, 2)->unsigned();
            $table->text('condition')->nullable();
            $table->integer('stock')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
