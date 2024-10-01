<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Compra;
use App\Models\User;
use App\Models\Livro;
use App\Models\CompraLivro;

class CompraTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function pode_criar_uma_compra()
    {
        // Criar um usuário para a compra
        $user = User::factory()->create();

        // Criar uma compra
        Compra::factory()->create([
            'user_id' => $user->id,
            'valor_compra' => 100,
            'valor_pago' => 10,
            'desconto' => 100 * 10 / 100,
            'transacao_sucesso' => true,
        ]);

        // Verificar se a compra foi criada com os atributos corretos
        $this->assertDatabaseHas('compras', [
            'user_id' => $user->id,
            'desconto' => 10,
            'transacao_sucesso' => true,
        ]);
    }

    /** @test */
    public function compra_pode_ter_livros_associados()
    {
        // Criar uma compra e três livros
        $compra = Compra::factory()->create();
        $livros = Livro::factory()->count(3)->create();

        // Associar a compra aos livros
        foreach ($livros as $livro) {
            CompraLivro::create([
                'compra_codcompra' => $compra->codcompra,
                'livro_codl' => $livro->codl,
                'quantidade' => 1,
            ]);
        }

        // Verificar se os livros foram associados à compra
        foreach ($livros as $livro) {
            $this->assertDatabaseHas('compra_livros', [
                'compra_codcompra' => $compra->codcompra,
                'livro_codl' => $livro->codl,
            ]);
        }
    }
}
