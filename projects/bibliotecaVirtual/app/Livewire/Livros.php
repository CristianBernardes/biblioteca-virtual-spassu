<?php

namespace App\Livewire;

use App\Services\LivroService;
use Livewire\Component;
use Livewire\WithPagination;

class Livros extends Component
{
    use WithPagination;

    public $termoPesquisa = ''; // Campo para o termo de pesquisa
    protected $livroServico;

    // Usamos o construtor para garantir a inicialização correta
    public function __construct()
    {
        $this->livroServico = new LivroService();
    }

    public function atualizandoTermoPesquisa()
    {
        // Reseta a página ao alterar o termo de pesquisa
        $this->resetPage();
    }

    public function render()
    {
        // Obtém a lista de livros com base no termo de pesquisa
        $livros = $this->livroServico->listarLivros($this->termoPesquisa, 'livro');

        return view('livewire.livros', [
            'livros' => $livros
        ]);
    }
}
