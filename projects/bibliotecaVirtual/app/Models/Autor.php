<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    use HasFactory;

    /**
     * Especifica o nome da tabela
     * @var string
     */
    protected $table = 'autor';

    /**
     * Especifica o nome da chave primária personalizada
     * @var string
     */
    protected $primaryKey = 'codau';

    /**
     * Define que a chave primária é autoincrementada
     * @var bool
     */
    public $incrementing = true;

    /**
     * Define o tipo da chave primária
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Define os campos que podem ser atribuídos em massa
     * @var string[]
     */
    protected $fillable = [
        'nome'
    ];

    // Relacionamentos

    /**
     * Relacionamento com livros (muitos-para-muitos)
     * @return BelongsToMany
     */
    public function livros()
    {
        return $this->belongsToMany(Livro::class, 'livro_autor', 'autor_codau', 'livro_codl');
    }
}
