<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LivroAutor extends Pivot
{
    /**
     * Especifica o nome da tabela
     * @var string
     */
    protected $table = 'livro_autor';

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
        'livro_codl',
        'autor_codau'
    ];
}
