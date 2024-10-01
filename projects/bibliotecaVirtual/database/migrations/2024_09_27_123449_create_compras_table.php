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
        Schema::create('compras', function (Blueprint $table) {
            $table->integer('codcompra')->unsigned()->autoIncrement()->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('transacao_sucesso')->default(false);
            $table->decimal('valor_compra', 10, 2);
            $table->decimal('valor_pago', 10, 2);
            $table->integer('desconto')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
