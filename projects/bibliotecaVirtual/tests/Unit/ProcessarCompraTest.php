<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Livro;

class ProcessarCompraTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function procedure_processar_compra_insere_corretamente_uma_nova_compra()
    {
        // Criar um usuário
        $user = User::factory()->create();

        // Criar três livros
        $livros = Livro::factory()->createMany([
            ['valor' => 100],
            ['valor' => 150],
            ['valor' => 200],
        ]);

        // Gerar os dados para a procedure (livro_id e quantidade em JSON)
        $livrosComprados = $livros->map(function ($livro) {
            return ['livro_id' => $livro->codl, 'quantidade' => 1];
        })->toJson();

        // Gerar um desconto aleatório entre 0 e 15
        $desconto = 10; // Usando um desconto fixo de 10% para o teste

        // Chamando a procedure e obtendo o ID da compra
        DB::select('CALL ProcessarCompra(?, ?, ?, @compraId)', [$user->id, $desconto, $livrosComprados]);

        // Obter o ID da compra inserida
        $compraId = DB::select('SELECT @compraId AS compraId')[0]->compraId;

        // Verificar se a compra foi inserida corretamente na tabela compras
        $this->assertDatabaseHas('compras', [
            'codcompra' => $compraId,
            'user_id' => $user->id,
            'desconto' => $desconto,
            'transacao_sucesso' => 1,
        ]);

        // Verificar se os livros foram associados corretamente à compra
        foreach ($livros as $livro) {
            $this->assertDatabaseHas('compra_livros', [
                'compra_codcompra' => $compraId,
                'livro_codl' => $livro->codl,
                'quantidade' => 1,
            ]);
        }
    }

    /** @test */
    public function procedure_processar_compra_calcula_o_valor_corretamente_com_desconto()
    {
        // Criar um usuário
        $user = User::factory()->create();

        // Criar três livros
        $livros = Livro::factory()->createMany([
            ['valor' => 100],
            ['valor' => 150],
            ['valor' => 200],
        ]);

        // Gerar os dados para a procedure (livro_id e quantidade em JSON)
        $livrosComprados = $livros->map(function ($livro) {
            return ['livro_id' => $livro->codl, 'quantidade' => 1];
        })->toJson();

        // Gerar um desconto de 10%
        $desconto = 10;

        // Chamando a procedure e obtendo o ID da compra
        DB::select('CALL ProcessarCompra(?, ?, ?, @compraId)', [$user->id, $desconto, $livrosComprados]);

        // Obter o ID da compra inserida
        $compraId = DB::select('SELECT @compraId AS compraId')[0]->compraId;

        // Verificar o valor total da compra com o desconto aplicado
        $valorTotalSemDesconto = 100 + 150 + 200; // 450
        $valorComDesconto = $valorTotalSemDesconto - ($valorTotalSemDesconto * $desconto / 100); // 450 - 10%

        // Verificar se o valor da compra foi calculado corretamente
        $this->assertDatabaseHas('compras', [
            'codcompra' => $compraId,
            'valor_compra' => $valorComDesconto,
            'desconto' => $desconto,
        ]);
    }
}
