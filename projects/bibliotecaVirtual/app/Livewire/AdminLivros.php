<?php

namespace App\Livewire;

use App\Services\LivroService;
use App\Services\AutorService;
use App\Services\AssuntosService;
use App\Traits\IziToastTrait;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Exception;

class AdminLivros extends Component
{
    use WithFileUploads, IziToastTrait;

    public $titulo, $editora, $edicao, $ano_publicacao, $valor, $autores = [], $assuntos = [], $capa;
    public $livros = [];
    public $livroId = null;

    protected $livroService, $autorService, $assuntosService;

    public function __construct()
    {
        $this->livroService = new LivroService();
        $this->autorService = new AutorService();
        $this->assuntosService = new AssuntosService();
    }

    public function mount()
    {
        $this->carregarLivros();
    }

    public function carregarLivros()
    {
        $this->livros = $this->livroService->getLivros();
    }

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

    public function submit()
    {
        $validatedData = $this->validate();

        try {
            if ($this->capa) {
                $validatedData['capa'] = $this->processarCapa($this->capa, $this->livroId);
            }

            if ($this->livroId) {
                $this->livroService->updateLivro($this->livroId, $validatedData, $this->autores, $this->assuntos);
                $this->enviarNotificacao('Sucesso', 'Livro atualizado com sucesso!', 'green');
            } else {
                $this->livroService->createLivro($validatedData, $this->autores, $this->assuntos);
                $this->enviarNotificacao('Sucesso', 'Livro cadastrado com sucesso!', 'green');
            }

            $this->resetForm();
            $this->carregarLivros();
        } catch (Exception $e) {
            $this->enviarNotificacao('Erro', $e->getMessage(), 'red');
        }
    }

    public function resetForm()
    {
        $this->reset(['titulo', 'editora', 'edicao', 'ano_publicacao', 'valor', 'autores', 'assuntos', 'capa', 'livroId']);
    }

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

    public function render()
    {
        return view('livewire.admin-livros', [
            'autoresDisponiveis' => $this->autorService->getAutores(),
            'assuntosDisponiveis' => $this->assuntosService->getAssuntos(),
            'livros' => $this->livros,
        ]);
    }
}
