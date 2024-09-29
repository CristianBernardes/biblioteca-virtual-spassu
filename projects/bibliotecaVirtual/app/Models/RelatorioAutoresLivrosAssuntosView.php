<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatorioAutoresLivrosAssuntosView extends Model
{
    // O model é associado à view no banco de dados
    protected $table = 'relatorio_autores_livros_assuntos_view';

    // Como é uma view, o Laravel não deve gerenciar timestamps para ela
    public $timestamps = false;

    // Impede operações de escrita
    protected $guarded = [];

    // O model não deve usar IDs incrementais
    public $incrementing = false;
}
