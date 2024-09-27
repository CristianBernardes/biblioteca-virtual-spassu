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
        Schema::create('livro_capa', function (Blueprint $table) {
            $table->integer('codcapa')->unsigned()->autoIncrement()->primary(); // Chave primária autoincrementada personalizada
            $table->integer('livro_codl')->unsigned(); // Chave estrangeira para 'livro'
            $table->string('caminho_imagem'); // Caminho do arquivo da capa
            $table->timestamps();

            // Definindo a chave estrangeira
            $table->foreign('livro_codl')->references('codl')->on('livro')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_capa');
    }
};
