<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprasView extends Model
{
    use HasFactory;

    /**
     * Especifica o nome da view
     * @var string
     */
    protected $table = 'compras_view';

    /**
     * Define que essa model não possui uma chave primária
     * @var string|null
     */
    protected $primaryKey = null;

    /**
     * Define que a chave primária não é autoincrementada
     * @var bool
     */
    public $incrementing = false;

    /**
     * Define que essa model não possui timestamps
     * @var bool
     */
    public $timestamps = false;

    protected $casts = [
        'livros_comprados' => 'array'
    ];
}
