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
            CREATE VIEW compras_view AS
                SELECT
                    users.name AS usuario,
                    compras.codcompra AS codigo_compra,
                    GROUP_CONCAT(livro.titulo SEPARATOR ', ') AS livros_comprados,
                    compras.valor_compra AS valor_total_livros,
                    compras.desconto AS desconto,
                    (compras.valor_compra - (compras.valor_compra * compras.desconto / 100)) AS valor_total_pago,
                    compras.transacao_sucesso AS sucesso,
                    compras.created_at AS data_compra
                FROM
                    compras
                JOIN
                    users ON users.id = compras.user_id
                JOIN
                    compra_livros ON compra_livros.compra_codcompra = compras.codcompra
                JOIN
                    livro ON livro.codl = compra_livros.livro_codl
                WHERE
                    compras.transacao_sucesso = 1
                GROUP BY
                    compras.codcompra, users.name, compras.valor_compra, compras.desconto, compras.transacao_sucesso, compras.created_at;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS compras_view");
    }
};
