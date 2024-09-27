<?php

namespace Tests\Unit;

use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;
use App\Models\LivroCapa;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use DatabaseTransactions; // Apenas reverte as mudanças feitas no banco de dados durante os testes

    /** @test */
    public function pode_criar_um_livro()
    {
        Livro::factory()->create([
            'titulo' => 'Livro Teste',
            'editora' => 'Editora Teste',
            'edicao' => 1,
            'ano_publicacao' => '2024',
            'valor' => 99.99,
        ]);

        $this->assertDatabaseHas('livro', [
            'titulo' => 'Livro Teste',
            'editora' => 'Editora Teste',
            'edicao' => 1,
            'ano_publicacao' => '2024',
            'valor' => 99.99,
        ]);
    }

    /** @test */
    public function livro_pode_ter_autores_associados()
    {
        $livro = Livro::factory()->create();
        $autores = Autor::factory()->count(2)->create();

        $livro->autores()->attach($autores);

        foreach ($autores as $autor) {
            $this->assertDatabaseHas('livro_autor', [
                'livro_codl' => $livro->codl,
                'autor_codau' => $autor->codau,
            ]);
        }
    }

    /** @test */
    public function livro_pode_ter_assuntos_associados()
    {
        // Garantir que o estado anterior não interfira
        $this->artisan('migrate:fresh');

        // Criação de um livro
        $livro = Livro::factory()->create();

        // Criando assuntos associados ao livro
        $assuntos = Assunto::factory()->count(3)->create();

        // Associar os assuntos ao livro
        $livro->assuntos()->attach($assuntos);

        // Verificar se a associação está correta
        foreach ($assuntos as $assunto) {
            $this->assertDatabaseHas('livro_assunto', [
                'livro_codl' => $livro->codl,
                'assunto_codas' => $assunto->codas,
            ]);
        }
    }

    /** @test */
    public function livro_pode_ter_uma_capa()
    {
        $livro = Livro::factory()->create();
        $capa = LivroCapa::factory()->create(['livro_codl' => $livro->codl]);

        $this->assertEquals($livro->capa->codcapa, $capa->codcapa);
        $this->assertDatabaseHas('livro_capa', [
            'livro_codl' => $livro->codl,
        ]);
    }
}
