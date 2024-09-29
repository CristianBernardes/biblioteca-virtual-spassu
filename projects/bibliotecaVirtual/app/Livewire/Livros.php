<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\LivroService;
use App\Traits\IziToastTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Livros
 *
 * Componente Livewire para mostragem de livros.
 */
class Livros extends Component
{
    use WithPagination, IziToastTrait;

    /**
     * @var string Termo de pesquisa para filtrar livros
     */
    public $termoPesquisa = '';

    /**
     * @var bool Flag para mostrar ou ocultar o botão de adicionar ao carrinho
     */
    public $mostrarBotaoAddCarrinho = false;

    /**
     * @var int Quantidade de itens no carrinho
     */
    public $itensCarrinho = 0;

    /**
     * @var null|User Usuário autenticado
     */
    private $user;

    /**
     * @var LivroService Serviço de gerenciamento de livros
     */
    protected $livroServico;

    /**
     * Construtor do componente.
     *
     * Inicializa o serviço de livro e define o usuário autenticado.
     */
    public function __construct()
    {
        $this->livroServico = new LivroService();
        $this->user = auth()->check() ? auth()->user() : null;
    }

    /**
     * Método executado ao atualizar o termo de pesquisa.
     *
     * Reseta a página quando o termo de pesquisa é alterado.
     */
    public function atualizandoTermoPesquisa()
    {
        $this->resetPage();
    }

    /**
     * Método para carregar a quantidade de itens no carrinho.
     */
    public function mount()
    {
        // Carrega a quantidade de itens no carrinho ao montar o componente
        $this->itensCarrinho = count(session()->get('carrinho', []));
    }

    /**
     * Renderiza a lista de livros.
     *
     * Define a exibição do botão de adicionar ao carrinho com base nas permissões do usuário.
     *
     * @return View
     */
    public function render()
    {
        if ($this->user && !$this->user->is_admin) {
            $this->mostrarBotaoAddCarrinho = true;
        }

        // Obtém a lista de livros com base no termo de pesquisa
        $livros = $this->livroServico->listarLivros($this->termoPesquisa, 'livro');

        return view('livewire.livros', [
            'livros' => $livros
        ]);
    }

    /**
     * Adiciona um livro ao carrinho de compras.
     *
     * @param string $livroCodl Código do livro a ser adicionado ao carrinho
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function adicionarAoCarrinho($livroCodl)
    {
        // Obtém o carrinho da sessão, se não existir, cria um array vazio
        $carrinho = session()->get('carrinho', []);

        // Verifica se o item já está no carrinho
        if (in_array($livroCodl, $carrinho)) {
            // Notifica que o item já está no carrinho
            $this->enviarNotificacao('Erro', 'Este livro já está no carrinho!', 'red');
        } else {
            // Adiciona o livro ao carrinho na sessão
            $carrinho[] = $livroCodl;
            session()->put('carrinho', $carrinho);

            // Atualiza a quantidade de itens no carrinho
            $this->itensCarrinho = count($carrinho);

            // Notifica o usuário sobre o sucesso
            $this->enviarNotificacao('Sucesso', 'Livro adicionado com sucesso!');
        }
    }
}
