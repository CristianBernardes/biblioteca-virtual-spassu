<?php

namespace App\Livewire;

use App\Services\LivroService;
use App\Traits\IziToastTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

/**
 * Class CarrinhoDeCompras
 *
 * Componente Livewire para gerenciar o carrinho de compras.
 */
class CarrinhoDeCompras extends Component
{
    use IziToastTrait;

    /**
     * @var array IDs dos livros no carrinho.
     */
    public $carrinho = [];

    /**
     * @var array Quantidade de cada livro no carrinho.
     */
    public $quantidades = [];

    /**
     * @var float Total do valor do carrinho.
     */
    public $total = 0;

    /**
     * @var string Código do cupom de desconto aplicado.
     */
    public $cupom = '';

    /**
     * @var int Valor percentual do desconto aplicado.
     */
    public $desconto = 0;

    /**
     * @var LivroService Serviço para manipulação de livros.
     */
    protected $livroServico;

    /**
     * Construtor do componente CarrinhoDeCompras.
     *
     * Inicializa o serviço de livros.
     */
    public function __construct()
    {
        $this->livroServico = new LivroService();
    }

    /**
     * Monta o componente.
     *
     * Inicializa os itens no carrinho e verifica se há um desconto aplicado.
     */
    public function mount()
    {
        $this->carrinho = session()->get('carrinho', []);
        $this->quantidades = array_fill_keys($this->carrinho, 1);
        $this->desconto = session()->get('desconto', 0);
        $this->atualizarTotal();
    }

    /**
     * Atualiza a quantidade de um livro no carrinho.
     *
     * @param int $codl Código do livro.
     * @param int $novaQuantidade Nova quantidade para o livro.
     */
    public function atualizarQuantidade($codl, $novaQuantidade)
    {
        if (isset($this->quantidades[$codl])) {
            $this->quantidades[$codl] = $novaQuantidade > 0 ? $novaQuantidade : 1;
        }
        $this->atualizarTotal();
    }

    /**
     * Recalcula o total do carrinho, aplicando o desconto se houver.
     */
    public function atualizarTotal()
    {
        $livros = $this->livroServico->listarLivrosPorCodl($this->carrinho);
        $this->total = 0;

        foreach ($livros as $livro) {
            $this->total += $livro->valor * $this->quantidades[$livro->codl];
        }

        if ($this->desconto > 0) {
            $this->total -= $this->total * ($this->desconto / 100);
        }
    }

    /**
     * Aplica um cupom de desconto ao carrinho.
     */
    public function aplicarCupom()
    {
        if (session()->has('desconto')) {
            $this->enviarNotificacao('Erro', 'Você já aplicou um cupom de desconto.', 'red');
            return;
        }

        $descontoAleatorio = rand(0, 15);

        if ($descontoAleatorio === 0) {
            $this->enviarNotificacao('Erro', 'Cupom não existe!', 'red');
        } else {
            $this->desconto = $descontoAleatorio;
            session()->put('desconto', $this->desconto);
            $this->enviarNotificacao('Sucesso', "Cupom aplicado! Desconto de {$this->desconto}% concedido.", 'green');
        }

        $this->atualizarTotal();
    }

    /**
     * Remove um livro do carrinho.
     *
     * @param int $codl Código do livro a ser removido.
     */
    public function removerDoCarrinho($codl)
    {
        if (($key = array_search($codl, $this->carrinho)) !== false) {
            unset($this->carrinho[$key]);
            unset($this->quantidades[$codl]);
            session()->put('carrinho', $this->carrinho);
            $this->atualizarTotal();

            if (empty($this->carrinho)) {
                session()->forget('desconto');
                return redirect()->route('welcome');
            }
        }
    }

    /**
     * Processa a compra e limpa o carrinho e o cupom de desconto.
     *
     * @return RedirectResponse Redireciona para a página 'welcome'.
     */
    public function comprar()
    {
        $user = auth()->user();
        $livrosComprados = collect($this->carrinho)->map(function ($codl) {
            return ['livro_id' => $codl, 'quantidade' => $this->quantidades[$codl]];
        })->toJson();

        DB::select('CALL ProcessarCompra(?, ?, ?, @compraId)', [$user->id, $this->desconto, $livrosComprados]);

        session()->forget('carrinho');
        session()->forget('desconto');

        return redirect()->route('welcome');
    }

    /**
     * Renderiza o componente Livewire.
     *
     * @return View
     */
    public function render()
    {
        $livros = $this->livroServico->listarLivrosPorCodl($this->carrinho);

        return view('livewire.carrinho-de-compras', [
            'livros' => $livros,
            'quantidades' => $this->quantidades,
            'total' => $this->total,
        ]);
    }
}
