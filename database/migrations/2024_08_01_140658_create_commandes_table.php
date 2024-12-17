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
            $table->Date('dateCommande');
            $table->string('etat')->default('en attente');
            $table->integer('montantTotal');
            $table->integer('quantite')->nullable(false);

//            $table->foreignIdFor(\App\Models\Burger::class)->constrained()->restrictOnDelete();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('burger_id')->constrained('burgers')->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
