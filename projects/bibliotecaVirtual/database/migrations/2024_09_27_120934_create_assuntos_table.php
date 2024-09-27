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
        Schema::create('assunto', function (Blueprint $table) {
            $table->integer('codas')->unsigned()->autoIncrement()->primary(); // chave primária autoincrementada
            $table->string('descricao'); // descrição do assunto
            $table->timestamps(); // timestamps para created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assunto');
    }
};
