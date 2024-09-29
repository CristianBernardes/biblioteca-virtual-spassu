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
            CREATE VIEW relatorio_autores_livros_assuntos_view AS
            SELECT
                livro.titulo AS titulo_livro,
                livro.editora AS editora_livro,
                livro.ano_publicacao AS ano_publicacao_livro,
                GROUP_CONCAT(DISTINCT autor.nome ORDER BY autor.nome SEPARATOR ', ') AS autores,
                GROUP_CONCAT(DISTINCT assunto.descricao ORDER BY assunto.descricao SEPARATOR ', ') AS assuntos
            FROM
                livro
            JOIN livro_autor ON livro.codl = livro_autor.livro_codl
            JOIN autor ON livro_autor.autor_codau = autor.codau
            JOIN livro_assunto ON livro.codl = livro_assunto.livro_codl
            JOIN assunto ON livro_assunto.assunto_codas = assunto.codas
            GROUP BY
                livro.codl
            ORDER BY
                livro.titulo;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS relatorio_autores_livros_assuntos_view");
    }
};
