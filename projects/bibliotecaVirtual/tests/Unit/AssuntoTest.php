<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Assunto;
use App\Models\Livro;

class AssuntoTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function pode_criar_um_assunto_com_descricao_unica()
    {
        // Criação de um assunto com descrição única
        Assunto::factory()->create([
            'descricao' => 'Assunto Único',
        ]);

        // Verificar se o assunto foi criado com os dados corretos
        $this->assertDatabaseHas('assunto', [
            'descricao' => 'Assunto Único',
        ]);
    }

    /** @test */
    public function assunto_pode_ter_livros_associados()
    {
        // Criar um assunto e três livros
        $assunto = Assunto::factory()->create();
        $livros = Livro::factory()->count(3)->create();

        // Associar o assunto aos livros
        $assunto->livros()->attach($livros);

        // Verificar se os livros foram associados ao assunto
        foreach ($livros as $livro) {
            $this->assertDatabaseHas('livro_assunto', [
                'assunto_codas' => $assunto->codas,
                'livro_codl' => $livro->codl,
            ]);
        }
    }
}
