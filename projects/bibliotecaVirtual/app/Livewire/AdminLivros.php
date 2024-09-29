<?php

namespace App\Livewire;

use App\Services\LivroService;
use App\Services\AutorService;
use App\Services\AssuntosService;
use App\Traits\IziToastTrait;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Exception;

/**
 * Class AdminLivros
 *
 * Componente Livewire para o gerenciamento de livros.
 */
class AdminLivros extends Component
{
    use WithFileUploads, IziToastTrait;

    /**
     * @var string $titulo Título do livro.
     * @var string $editora Nome da editora.
     * @var int $edicao Edição do livro.
     * @var string $ano_publicacao Ano de publicação do livro.
     * @var float $valor Valor do livro.
     * @var array $autores Lista de autores selecionados.
     * @var array $assuntos Lista de assuntos selecionados.
     * @var mixed $capa Arquivo de capa carregado pelo usuário.
     * @var array $livros Lista de livros cadastrados.
     * @var int|null $livroId ID do livro que está sendo editado.
     */
    public $titulo, $editora, $edicao, $ano_publicacao, $valor, $autores = [], $assuntos = [], $capa;
    public $livros = [];
    public $livroId = null;

    /**
     * @var LivroService Serviço responsável pelos livros.
     * @var AutorService Serviço responsável pelos autores.
     * @var AssuntosService Serviço responsável pelos assuntos.
     */
    protected $livroService, $autorService, $assuntosService;

    /**
     * AdminLivros constructor.
     *
     * Inicializa os serviços necessários.
     */
    public function __construct()
    {
        $this->livroService = new LivroService();
        $this->autorService = new AutorService();
        $this->assuntosService = new AssuntosService();
    }

    /**
     * Método chamado ao montar o componente.
     * Carrega os livros cadastrados.
     */
    public function mount()
    {
        $this->carregarLivros();
    }

    /**
     * Carrega a lista de livros.
     */
    public function carregarLivros()
    {
        $this->livros = $this->livroService->getLivros();
    }

    /**
     * Define as regras de validação para o formulário.
     *
     * @return array Regras de validação.
     */
    public function rules()
    {
        return [
            'titulo' => 'required|string|max:40',
            'editora' => 'required|string|max:40',
            'edicao' => 'required|integer|min:1',
            'ano_publicacao' => 'required|string|size:4',
            'valor' => 'required|numeric|min:0',
            'autores' => 'required|array|min:1',
            'assuntos' => 'required|array|min:1',
            'capa' => 'nullable|image|max:1024',
        ];
    }

    /**
     * Submete o formulário para cadastrar ou atualizar o livro.
     */
    public function submit()
    {
        $validatedData = $this->validate();

        try {
            if ($this->capa) {
                $validatedData['capa'] = $this->processarCapa($this->capa, $this->livroId);
            }

            if ($this->livroId) {
                // Atualizar livro existente
                $this->livroService->updateLivro($this->livroId, $validatedData, $this->autores, $this->assuntos);
                $this->enviarNotificacao('Sucesso', 'Livro atualizado com sucesso!', 'green');
            } else {
                // Criar novo livro
                $this->livroService->createLivro($validatedData, $this->autores, $this->assuntos);
                $this->enviarNotificacao('Sucesso', 'Livro cadastrado com sucesso!', 'green');
            }

            $this->resetForm();
            $this->carregarLivros();
        } catch (Exception $e) {
            $this->enviarNotificacao('Erro', $e->getMessage(), 'red');
        }
    }

    /**
     * Reseta o formulário após o envio ou edição.
     */
    public function resetForm()
    {
        $this->reset(['titulo', 'editora', 'edicao', 'ano_publicacao', 'valor', 'autores', 'assuntos', 'capa', 'livroId']);
    }

    /**
     * Processa o upload da capa do livro, excluindo a anterior se houver.
     *
     * @param mixed $capa Arquivo de capa enviado pelo usuário.
     * @param int|null $livroId ID do livro, caso esteja atualizando.
     * @return string Nome do arquivo salvo.
     * @throws Exception Se ocorrer erro ao salvar a capa.
     */
    public function processarCapa($capa, $livroId = null)
    {
        try {
            $path = 'capas';
            if ($livroId) {
                $livro = $this->livroService->getLivroById($livroId);
                if ($livro->capa) {
                    Storage::delete($path . '/' . $livro->capa);
                }
            }

            if (!Storage::exists($path)) {
                Storage::makeDirectory($path);
            }

            $nomeCapa = uniqid() . '.' . $capa->getClientOriginalExtension();
            Storage::putFileAs($path, $capa, $nomeCapa);

            return $nomeCapa;
        } catch (Exception $e) {
            throw new Exception('Erro ao salvar a capa: ' . $e->getMessage());
        }
    }

    /**
     * Carrega os dados de um livro para edição.
     *
     * @param int $livroId ID do livro.
     */
    public function editarLivro($livroId)
    {
        $livro = $this->livroService->getLivroById($livroId);
        $this->livroId = $livro->codl;
        $this->titulo = $livro->titulo;
        $this->editora = $livro->editora;
        $this->edicao = $livro->edicao;
        $this->ano_publicacao = $livro->ano_publicacao;
        $this->valor = $livro->valor;
        $this->autores = $livro->autores->pluck('codau')->toArray();
        $this->assuntos = $livro->assuntos->pluck('codas')->toArray();
    }

    /**
     * Deleta um livro e sua capa do sistema.
     *
     * @param int $livroId ID do livro a ser deletado.
     */
    public function deletarLivro($livroId)
    {
        try {
            $this->livroService->deleteLivro($livroId);
            $this->enviarNotificacao('Sucesso', 'Livro deletado com sucesso!', 'green');
            $this->carregarLivros();
        } catch (Exception $e) {
            $this->enviarNotificacao('Erro', $e->getMessage(), 'red');
        }
    }

    /**
     * Renderiza a view com os dados de livros, autores e assuntos.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin-livros', [
            'autoresDisponiveis' => $this->autorService->getAutores(),
            'assuntosDisponiveis' => $this->assuntosService->getAssuntos(),
            'livros' => $this->livros,
        ]);
    }

    #[On('atualizarComponente')]
    public function refresh()
    {
    }
}
