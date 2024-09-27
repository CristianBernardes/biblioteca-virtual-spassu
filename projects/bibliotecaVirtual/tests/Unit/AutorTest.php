<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Autor;
use App\Models\Livro;

class AutorTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function pode_criar_um_autor()
    {
        // Criação de um autor
        Autor::factory()->create([
            'nome' => 'Autor Teste',
        ]);

        // Verificar se o autor foi criado com os dados corretos
        $this->assertDatabaseHas('autor', [
            'nome' => 'Autor Teste',
        ]);
    }

    /** @test */
    public function autor_pode_ter_livros_associados()
    {
        // Criar um autor e três livros
        $autor = Autor::factory()->create();
        $livros = Livro::factory()->count(3)->create();

        // Associar o autor aos livros
        $autor->livros()->attach($livros);

        // Verificar se os livros foram associados ao autor
        foreach ($livros as $livro) {
            $this->assertDatabaseHas('livro_autor', [
                'autor_codau' => $autor->codau,
                'livro_codl' => $livro->codl,
            ]);
        }
    }
}
