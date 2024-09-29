<?php

namespace App\Services;

use App\Models\Livro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class LivroService
 *
 * Serviço responsável pelo gerenciamento dos livros.
 */
class LivroService
{
    /**
     * @var Livro $livro Instância do model Livro.
     */
    private Livro $livro;

    /**
     * LivroService constructor.
     *
     * Inicializa o model de Livro.
     */
    public function __construct()
    {
        $this->livro = new Livro();
    }

    /**
     * Retorna uma query base de livros com as relações necessárias.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryLivros()
    {
        return $this->livro->with('capa', 'autores', 'assuntos');
    }

    public function getLivros()
    {
        return $this->queryLivros()->get();
    }

    /**
     * Lista livros filtrados por título, autor ou assunto.
     *
     * @param string|null $termoBusca
     * @param string|null $tipoFiltro
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listarLivros(?string $termoBusca = null, ?string $tipoFiltro = null)
    {
        return $this->queryLivros()
            ->when($tipoFiltro !== null && $tipoFiltro === 'livro', function ($query) use ($termoBusca) {
                $query->where('titulo', 'like', '%' . $termoBusca . '%');
            })
            ->when($tipoFiltro !== null && $tipoFiltro === 'autor', function ($query) use ($termoBusca) {
                $query->whereHas('autores', function ($query) use ($termoBusca) {
                    $query->where('nome', 'like', '%' . $termoBusca . '%');
                });
            })
            ->when($tipoFiltro !== null && $tipoFiltro === 'assunto', function ($query) use ($termoBusca) {
                $query->whereHas('assuntos', function ($query) use ($termoBusca) {
                    $query->where('descricao', 'like', '%' . $termoBusca . '%');
                });
            })
            ->paginate(10);
    }

    /**
     * Lista livros por seus códigos (codl).
     *
     * @param array|int $codls
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listarLivrosPorCodl(mixed $codls)
    {
        return $this->queryLivros()->whereIn('codl', (array)$codls)->get();
    }

    /**
     * Cria um novo livro e associa autores, assuntos e capa.
     *
     * @param array $data
     * @param array $autores
     * @param array $assuntos
     * @return Livro
     * @throws \Exception
     */
    public function createLivro(array $data, array $autores, array $assuntos)
    {
        DB::beginTransaction();

        try {
            // Criação do livro
            $livro = $this->livro->create($data);

            $agora = now(); // Define o timestamp atual

            // Associação de autores e assuntos com timestamps
            $autoresSyncData = array_fill_keys($autores, ['created_at' => $agora, 'updated_at' => $agora]);
            $assuntosSyncData = array_fill_keys($assuntos, ['created_at' => $agora, 'updated_at' => $agora]);

            $livro->autores()->sync($autoresSyncData);
            $livro->assuntos()->sync($assuntosSyncData);

            // Associação da capa (se houver)
            if (isset($data['capa'])) {
                $livro->capa()->create([
                    'caminho_imagem' => $data['capa'],
                ]);
            }

            DB::commit();
            return $livro;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao criar o livro: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza um livro existente e suas relações.
     *
     * @param int $codl
     * @param array $data
     * @param array $autores
     * @param array $assuntos
     * @return Livro
     * @throws \Exception
     */
    public function updateLivro(int $codl, array $data, array $autores, array $assuntos)
    {
        DB::beginTransaction();

        try {
            $livro = $this->getLivroById($codl);

            // Atualiza os dados do livro
            $livro->update($data);

            // Atualiza as relações
            $livro->autores()->sync($autores);
            $livro->assuntos()->sync($assuntos);

            // Atualiza a capa (se houver)
            if (isset($data['capa'])) {
                if ($livro->capa) {
                    // Deleta a capa antiga
                    Storage::delete('capas/' . $livro->capa->caminho_imagem);
                    $livro->capa->update(['caminho_imagem' => $data['capa']]);
                } else {
                    // Cria uma nova capa
                    $livro->capa()->create(['caminho_imagem' => $data['capa']]);
                }
            }

            DB::commit();
            return $livro;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao atualizar o livro: ' . $e->getMessage());
        }
    }

    /**
     * Deleta um livro e suas relações.
     *
     * @param int $codl
     * @return bool
     * @throws \Exception
     */
    public function deleteLivro(int $codl)
    {
        try {
            $livro = $this->getLivroById($codl);

            // Remove a capa, se existir
            if ($livro->capa) {
                Storage::delete('capas/' . $livro->capa->caminho_imagem);
            }

            return $livro->delete();

        } catch (\Exception $e) {
            throw new \Exception('Erro ao deletar o livro: ' . $e->getMessage());
        }
    }

    public function getLivroById(int $codl)
    {
        return $this->livro->where('codl', $codl)->first();
    }
}
