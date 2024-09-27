<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('livro', function (Blueprint $table) {
            $table->integer('codl')->unsigned()->autoIncrement()->primary(); // chave primária manual
            $table->string('titulo', 40);
            $table->string('editora', 40);
            $table->integer('edicao');
            $table->string('ano_publicacao', 4);
            $table->decimal('valor', 8, 2); // adicionando o campo de valor para o livro
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro');
    }
};
