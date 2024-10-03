<?php

namespace App\Services;

use App\Models\Autor;
use Illuminate\Database\Eloquent\Collection;
use mysql_xdevapi\Exception;

/**
 * Class AutorService
 *
 * Serviço responsável pelo gerenciamento dos autores.
 */
class AutorService
{
    /**
     * @var Autor $model Instância do model Autor.
     */
    private Autor $model;

    /**
     * AutorService constructor.
     *
     * Inicializa o model de Autor.
     */
    public function __construct()
    {
        $this->model = new Autor();
    }

    /**
     * Retorna os autores ordenados alfabeticamente.
     *
     * @return Collection
     */
    public function getAutores()
    {
        return $this->model->orderBy('nome', 'asc')->get();
    }

    /**
     * Armazena um novo autor.
     *
     * @param array $data
     * @return Autor
     * @throws \Exception
     */
    public function storeAutor(array $data)
    {
        try {
            $autor = $this->model->create([
                'nome' => $data['nome']
            ]);

            return $autor;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Atualiza um autor existente.
     *
     * @param int $codau
     * @param array $data
     * @return Autor
     * @throws \Exception
     */
    public function updateAutor(int $codau, array $data)
    {
        try {
            $autor = $this->model->where('codau', $codau)->firstOrFail();
            $autor->update([
                'nome' => $data['nome']
            ]);

            return $autor;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Deleta um autor.
     *
     * @param int $codau
     * @return bool
     * @throws \Exception
     */
    public function deleteAutor(int $codau)
    {
        try {
            $autor = $this->model->with('livros')->where('codau', $codau)->firstOrFail();

            if (count($autor->livros) > 0) {
                throw new \Exception('Você não pode excluir um autor que esteja vinculado a um livro.');
            }

            return $autor->delete();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
