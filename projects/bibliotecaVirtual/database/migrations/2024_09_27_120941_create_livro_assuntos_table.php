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
        Schema::create('livro_assunto', function (Blueprint $table) {
            $table->integer('livro_codl')->unsigned(); // Chave estrangeira para 'livro'
            $table->integer('assunto_codas')->unsigned(); // Chave estrangeira para 'assunto'
            $table->timestamps();

            // Definindo as chaves estrangeiras
            $table->foreign('livro_codl')->references('codl')->on('livro')->onDelete('cascade');
            $table->foreign('assunto_codas')->references('codas')->on('assunto')->onDelete('cascade');

            // Garantindo que a combinação de livro e assunto seja única
            $table->unique(['livro_codl', 'assunto_codas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_assunto');
    }
};
