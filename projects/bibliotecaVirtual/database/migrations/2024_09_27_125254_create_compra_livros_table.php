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
        Schema::create('compra_livros', function (Blueprint $table) {
            $table->integer('compra_codcompra')->unsigned();
            $table->integer('livro_codl')->unsigned();
            $table->integer('quantidade')->unsigned()->default(1);
            $table->timestamps();
            $table->foreign('compra_codcompra')->references('codcompra')->on('compras')->onDelete('cascade');
            $table->foreign('livro_codl')->references('codl')->on('livro')->onDelete('cascade');
            $table->unique(['compra_codcompra', 'livro_codl']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra_livros');
    }
};
