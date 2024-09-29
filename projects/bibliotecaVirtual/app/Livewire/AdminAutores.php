<?php

namespace App\Livewire;

use App\Services\AutorService;
use Illuminate\View\View;
use Livewire\Component;
use App\Traits\IziToastTrait;

/**
 * Class AdminAutores
 *
 * Componente Livewire responsável pelo gerenciamento dos autores.
 */
class AdminAutores extends Component
{
    use IziToastTrait;

    /**
     * @var string $nome Armazena o nome do autor.
     */
    public $nome;

    /**
     * @var array $autores Lista de autores cadastrados.
     */
    public $autores = [];

    /**
     * @var int|null $autorId Armazena o ID do autor que está sendo atualizado.
     */
    public $autorId;

    /**
     * @var AutorService Serviço responsável pela manipulação dos autores.
     */
    protected $autorService;

    /**
     * AdminAutores constructor.
     *
     * Inicializa o serviço de autores.
     */
    public function __construct()
    {
        $this->autorService = new AutorService();
    }

    /**
     * Método chamado ao montar o componente.
     *
     * Carrega a lista de autores.
     *
     * @return void
     */
    public function mount()
    {
        $this->carregarAutores();
    }

    /**
     * Carrega os autores em ordem alfabética.
     *
     * @return void
     */
    public function carregarAutores()
    {
        $this->autores = $this->autorService->getAutores()->toArray();
    }

    /**
     * Define as regras de validação para o campo de nome.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|string|max:40|unique:autor,nome,' . $this->autorId . ',codau',
        ];
    }

    /**
     * Submete o formulário para cadastrar ou atualizar o autor.
     *
     * @return void
     */
    public function submit()
    {
        $validatedData = $this->validate();

        try {
            if ($this->autorId) {
                // Atualiza o autor
                $this->autorService->updateAutor($this->autorId, $validatedData);
                $this->enviarNotificacao('Sucesso', 'Autor atualizado com sucesso!', 'green');
            } else {
                // Cadastra um novo autor
                $this->autorService->storeAutor($validatedData);
                $this->enviarNotificacao('Sucesso', 'Autor cadastrado com sucesso!', 'green');
            }

            // Reseta os campos do formulário
            $this->reset(['nome', 'autorId']);
            $this->dispatch('atualizarComponente');
            $this->carregarAutores();
        } catch (\Exception $e) {
            $this->enviarNotificacao('Erro', $e->getMessage(), 'red');
        }
    }

    /**
     * Prepara o formulário para atualização de um autor.
     *
     * @param int $id
     * @param string $nome
     * @return void
     */
    public function editarAutor($id, $nome)
    {
        $this->autorId = $id;
        $this->nome = $nome;
    }

    /**
     * Deleta um autor.
     *
     * @param int $id
     * @return void
     */
    public function deletarAutor($id)
    {
        try {
            $this->autorService->deleteAutor($id);
            $this->enviarNotificacao('Sucesso', 'Autor deletado com sucesso!', 'green');
            $this->carregarAutores();
            $this->dispatch('atualizarComponente');
        } catch (\Exception $e) {
            $this->enviarNotificacao('Erro', $e->getMessage(), 'red');
        }
    }

    /**
     * Renderiza a view com os dados de autores.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin-autores', [
            'autores' => $this->autores,
        ]);
    }
}
