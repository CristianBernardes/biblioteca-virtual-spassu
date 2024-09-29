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
                IN p_desconto INT,
                IN p_livros JSON,
                OUT p_compra_id INT
            )
            BEGIN
                DECLARE v_total DECIMAL(10,2) DEFAULT 0;
                DECLARE v_livro_id INT;
                DECLARE v_quantidade INT;
                DECLARE i INT DEFAULT 0;
                DECLARE json_length INT;

                -- Obtém o comprimento do JSON (quantidade de livros)
                SET json_length = JSON_LENGTH(p_livros);

                -- Cria a compra inicialmente sem calcular o valor
                INSERT INTO compras(user_id, valor_compra, desconto, transacao_sucesso, created_at, updated_at)
                VALUES (p_user_id, 0, p_desconto, 1, NOW(), NOW());

                -- Obtém o ID da compra recém inserida
                SET p_compra_id = LAST_INSERT_ID();

                -- Loop para inserir os livros associados e calcular o valor total
                WHILE i < json_length DO
                    SET v_livro_id = JSON_UNQUOTE(JSON_EXTRACT(p_livros, CONCAT('$[', i, '].livro_id')));
                    SET v_quantidade = JSON_UNQUOTE(JSON_EXTRACT(p_livros, CONCAT('$[', i, '].quantidade')));

                    -- Inserir na tabela compra_livros
                    INSERT INTO compra_livros (compra_codcompra, livro_codl, quantidade, created_at, updated_at)
                    VALUES (p_compra_id, v_livro_id, v_quantidade, NOW(), NOW());

                    -- Atualizar o valor total da compra
                    SET v_total = v_total + (SELECT valor * v_quantidade FROM livro WHERE codl = v_livro_id);

                    SET i = i + 1;
                END WHILE;

                -- Aplicar o desconto ao valor total
                SET v_total = v_total - (v_total * p_desconto / 100);

                -- Atualizar o valor total da compra na tabela compras
                UPDATE compras
                SET valor_compra = v_total
                WHERE codcompra = p_compra_id;
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
