<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->down();

        DB::statement("
            CREATE PROCEDURE ProcessarCompra(
                IN p_user_id INT,
                IN p_desconto INT
            )
            BEGIN
                DECLARE v_total DECIMAL(10,2) DEFAULT 0;
                DECLARE v_compra_id INT;

                -- Calcula o valor total dos livros na compra
                SELECT SUM(livro.valor * compra_livros.quantidade)
                INTO v_total
                FROM compra_livros
                JOIN livro ON livro.codl = compra_livros.livro_codl
                WHERE compra_livros.compra_codcompra = LAST_INSERT_ID();

                -- Aplica o desconto à compra
                SET v_total = v_total - (v_total * p_desconto / 100);

                -- Registra a compra na tabela de compras
                INSERT INTO compras(user_id, valor_compra, desconto, transacao_sucesso, created_at, updated_at)
                VALUES (p_user_id, v_total, p_desconto, 1, NOW(), NOW());

                -- Obtém o ID da compra recém inserida
                SET v_compra_id = LAST_INSERT_ID();

                -- Atualiza a tabela compra_livros para associar a compra aos livros comprados
                UPDATE compra_livros
                SET compra_codcompra = v_compra_id
                WHERE compra_codcompra = LAST_INSERT_ID();
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP PROCEDURE IF EXISTS ProcessarCompra");
    }
};
