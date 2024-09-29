<?php

namespace App\Livewire;

use App\Services\AssuntosService;
use Illuminate\View\View;
use Livewire\Component;
use App\Traits\IziToastTrait;

/**
 * Class AdminAssuntos
 *
 * Componente Livewire para gerenciar o cadastro, atualização e deleção de assuntos.
 */
class AdminAssuntos extends Component
{
    use IziToastTrait;

    /**
     * @var string $descricao Campo para armazenar a descrição do assunto.
     */
    public $descricao;

    /**
     * @var array $assuntos Armazena a lista de assuntos cadastrados.
     */
    public $assuntos = [];

    /**
     * @var int|null $assuntoId Armazena o ID do assunto que está sendo atualizado.
     */
    public $assuntoId;

    /**
     * @var AssuntosService $assuntosService Serviço responsável pela manipulação dos assuntos.
     */
    protected $assuntosService;

    /**
     * AdminAssuntos constructor.
     *
     * Inicializa o serviço de assuntos.
     */
    public function __construct()
    {
        $this->assuntosService = new AssuntosService();
    }

    /**
     * Método chamado ao montar o componente.
     *
     * Carrega a lista de assuntos em ordem alfabética.
     *
     * @return void
     */
    public function mount()
    {
        $this->carregarAssuntos();
    }

    /**
     * Carrega os assuntos ordenados alfabeticamente.
     *
     * @return void
     */
    public function carregarAssuntos()
    {
        $this->assuntos = $this->assuntosService->getAssuntos()->toArray();
    }

    /**
     * Define as regras de validação para o campo de descrição.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'descricao' => 'required|string|max:20|unique:assunto,descricao,' . $this->assuntoId . ',codas',
        ];
    }

    /**
     * Submete o formulário para cadastrar ou atualizar o assunto.
     *
     * @return void
     */
    public function submit()
    {
        $validatedData = $this->validate();

        try {
            if ($this->assuntoId) {
                // Atualiza o assunto
                $this->assuntosService->updateAssunto($this->assuntoId, $validatedData);
                $this->enviarNotificacao('Sucesso', 'Assunto atualizado com sucesso!', 'green');
            } else {
                // Cadastra um novo assunto
                $this->assuntosService->storeAssunto($validatedData);
                $this->enviarNotificacao('Sucesso', 'Assunto cadastrado com sucesso!', 'green');
            }

            // Reseta os campos do formulário
            $this->reset(['descricao', 'assuntoId']);
            $this->carregarAssuntos();
        } catch (\Exception $e) {
            $this->enviarNotificacao('Erro', $e->getMessage(), 'red');
        }
    }

    /**
     * Prepara o formulário para atualização de um assunto.
     *
     * @param int $id
     * @param string $descricao
     * @return void
     */
    public function editarAssunto($id, $descricao)
    {
        $this->assuntoId = $id;
        $this->descricao = $descricao;
    }

    /**
     * Deleta um assunto.
     *
     * @param int $id
     * @return void
     */
    public function deletarAssunto($id)
    {
        try {
            $this->assuntosService->deleteAssunto($id);
            $this->enviarNotificacao('Sucesso', 'Assunto deletado com sucesso!', 'green');
            $this->carregarAssuntos();
        } catch (\Exception $e) {
            $this->enviarNotificacao('Erro', $e->getMessage(), 'red');
        }
    }

    /**
     * Renderiza a view com os dados de assuntos.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin-assuntos', [
            'assuntos' => $this->assuntos,
        ]);
    }
}
