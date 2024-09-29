<?php

namespace App\Services;

use App\Models\Livro;

class LivroService
{
    private Livro $livro;

    public function __construct()
    {
        $this->livro = new Livro();
    }

    private function queryLivros()
    {
        return $this->livro->with('capa', 'autores', 'assuntos');
    }

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

    public function listarLivrosPorCodl(mixed $codls)
    {
        return $this->queryLivros()->whereIn('codl', $codls)->get();
    }
}
