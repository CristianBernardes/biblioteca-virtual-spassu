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
        Schema::create('livro_autor', function (Blueprint $table) {
            $table->integer('livro_codl')->unsigned(); // Chave estrangeira para 'livro'
            $table->integer('autor_codau')->unsigned(); // Chave estrangeira para 'autor'
            $table->timestamps();

            // Definindo as chaves estrangeiras
            $table->foreign('livro_codl')->references('codl')->on('livro')->onDelete('cascade');
            $table->foreign('autor_codau')->references('codau')->on('autor')->onDelete('cascade');

            // Garantindo que a combinação de livro e autor seja única
            $table->unique(['livro_codl', 'autor_codau']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_autor');
    }
};
