<?php

namespace App\Services;

use App\Models\Assunto;
use Illuminate\Database\Eloquent\Collection;

class AssuntosService
{
    private Assunto $model;

    public function __construct()
    {
        $this->model = new Assunto();
    }

    /**
     * Retorna os assuntos ordenados alfabeticamente.
     *
     * @return Collection
     */
    public function getAssuntos()
    {
        return $this->model->orderBy('descricao', 'asc')->get();
    }

    /**
     * Armazena um novo assunto.
     *
     * @param array $data
     * @return Assunto
     * @throws \Exception
     */
    public function storeAssunto(array $data)
    {
        try {
            $assunto = $this->model->create([
                'descricao' => $data['descricao']
            ]);

            return $assunto;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Atualiza um assunto existente.
     *
     * @param int $codas
     * @param array $data
     * @return Assunto
     * @throws \Exception
     */
    public function updateAssunto(int $codas, array $data)
    {
        try {
            $assunto = $this->model->where('codas', $codas)->firstOrFail();
            $assunto->update([
                'descricao' => $data['descricao']
            ]);

            return $assunto;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Deleta um assunto.
     *
     * @param int $codas
     * @return bool
     * @throws \Exception
     */
    public function deleteAssunto(int $codas)
    {
        try {
            $assunto = $this->model->where('codas', $codas)->firstOrFail();
            return $assunto->delete();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
