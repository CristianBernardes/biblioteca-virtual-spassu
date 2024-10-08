<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Livro;
use App\Models\Compra;
use App\Models\CompraLivro;
use App\Models\ComprasView;

class ComprasViewTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function view_compras_view_retorna_os_dados_corretamente()
    {
        // Criar um usuário
        $user = User::factory()->create(['name' => 'Usuário Teste']);

        // Criar três livros com valores específicos
        $livros = Livro::factory()->createMany([
            ['valor' => 100, 'titulo' => 'Livro 1'],
            ['valor' => 100, 'titulo' => 'Livro 2'],
            ['valor' => 100, 'titulo' => 'Livro 3'],
        ]);

        // Calcular o valor total dos livros
        $valorTotalLivros = $livros->sum('valor'); // 100 * 3 = 300

        $desconto = 10;

        // Criar uma compra com o valor calculado
        $compra = Compra::factory()->create([
            'user_id' => $user->id,
            'valor_compra' => $valorTotalLivros,
            'valor_pago' => $valorTotalLivros - (($valorTotalLivros * $desconto) / 100),
            'desconto' => $desconto,
            'transacao_sucesso' => true,
        ]);

        // Associar os livros à compra
        foreach ($livros as $livro) {
            CompraLivro::create([
                'compra_codcompra' => $compra->codcompra,
                'livro_codl' => $livro->codl,
                'quantidade' => 1,
            ]);
        }

        // Consultar a view usando a model ComprasView
        $result = ComprasView::where('codigo_compra', $compra->codcompra)->first();

        // Construir o array esperado com nome do livro e quantidade
        $livrosEsperados = $livros->map(function ($livro) {
            return [
                'nome_livro' => $livro->titulo,
                'quantidade' => 1
            ];
        })->toArray();  // Usamos toArray() em vez de toJson()

        // Validar se a view retorna os dados corretamente
        $this->assertEquals('Usuário Teste', $result->usuario);
        $this->assertEquals($livrosEsperados, $result->livros_comprados);  // Comparar diretamente o array
        $this->assertEquals(300, $result->valor_total_livros);  // Valor total dos livros deve ser 300
        $this->assertEquals(10, $result->desconto);
        $this->assertEquals(270, $result->valor_total_pago);  // 300 - 10% de desconto
        $this->assertEquals(1, $result->sucesso);  // Transação bem-sucedida
    }

    /** @test */
    public function view_compras_view_calcula_os_valores_corretamente()
    {
        // Criar um usuário
        $user = User::factory()->create();

        // Criar três livros com diferentes valores
        $livros = Livro::factory()->createMany([
            ['valor' => 50],
            ['valor' => 75],
            ['valor' => 125],
        ]);

        // Calcular o valor total dos livros
        $valorTotalLivros = $livros->sum('valor'); // 50 + 75 + 125 = 250

        $desconto = 15;

        // Criar uma compra com o valor calculado e desconto de 15%
        $compra = Compra::factory()->create([
            'user_id' => $user->id,
            'valor_compra' => $valorTotalLivros,
            'valor_pago' => $valorTotalLivros - (($valorTotalLivros * $desconto) / 100),
            'desconto' => $desconto,
            'transacao_sucesso' => true,
        ]);

        // Associar os livros à compra
        foreach ($livros as $livro) {
            CompraLivro::create([
                'compra_codcompra' => $compra->codcompra,
                'livro_codl' => $livro->codl,
                'quantidade' => 1,
            ]);
        }

        // Consultar a view usando a model ComprasView
        $result = ComprasView::where('codigo_compra', $compra->codcompra)->first();

        // Calcular o valor total pago após o desconto
        $valorTotalPago = $valorTotalLivros - ($valorTotalLivros * 0.15);  // 250 - 15%

        // Validar os cálculos de valor total e desconto
        $this->assertEquals($valorTotalLivros, $result->valor_total_livros);
        $this->assertEquals(15, $result->desconto);
        $this->assertEquals($valorTotalPago, $result->valor_total_pago);  // 250 - 15%
        $this->assertEquals(1, $result->sucesso);  // Transação bem-sucedida
    }
}
