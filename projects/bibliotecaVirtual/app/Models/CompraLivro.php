<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompraLivro extends Pivot
{
    use HasFactory;

    /**
     * Especifica o nome da tabela
     * @var string
     */
    protected $table = 'compra_livros';

    /**
     * Especifica que essa tabela não possui timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Define os campos que podem ser atribuídos em massa
     * @var string[]
     */
    protected $fillable = [
        'compra_codcompra',
        'livro_codl',
        'quantidade'
    ];
}
