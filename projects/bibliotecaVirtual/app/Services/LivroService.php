<?php

namespace App\Services;

use App\Models\Livro;

class LivroService
{
    public function listarLivros(?string $termoBusca = null, ?string $tipoFiltro = null)
    {
        return Livro::with('capa', 'autores', 'assuntos')
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
}
