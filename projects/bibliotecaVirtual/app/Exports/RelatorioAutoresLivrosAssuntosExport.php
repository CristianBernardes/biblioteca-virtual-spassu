<?php

namespace App\Exports;

use App\Models\RelatorioAutoresLivrosAssuntosView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RelatorioAutoresLivrosAssuntosExport implements FromCollection, WithHeadings
{
    /**
     * Retorna a coleção de dados da view para o Excel.
     */
    public function collection()
    {
        return RelatorioAutoresLivrosAssuntosView::all();
    }

    /**
     * Define os cabeçalhos das colunas.
     */
    public function headings(): array
    {
        return [
            'Título do Livro',
            'Editora do Livro',
            'Ano de Publicação',
            'Autores',
            'Assuntos'
        ];
    }
}
