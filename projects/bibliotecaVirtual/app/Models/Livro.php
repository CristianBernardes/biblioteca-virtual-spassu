<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Livro extends Model
{
    use HasFactory;

    /**
     * Especifica o nome da tabela
     * @var string
     */
    protected $table = 'livro';

    /**
     * Especifica o nome da chave primária personalizada
     * @var string
     */
    protected $primaryKey = 'codl';

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
        'titulo',
        'editora',
        'edicao',
        'ano_publicacao',
        'valor'
    ];

    // Relacionamentos

    /**
     * Relacionamento com autores (muitos-para-muitos)
     * @return BelongsToMany
     */
    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'livro_autor', 'livro_codl', 'autor_codau');
    }

    /**
     * Relacionamento com assuntos (muitos-para-muitos)
     * @return BelongsToMany
     */
    public function assuntos()
    {
        return $this->belongsToMany(Assunto::class, 'livro_assunto', 'livro_codl', 'assunto_codas');
    }

    /**
     * Relacionamento com capas (um-para-um)
     * @return HasOne
     */
    public function capa()
    {
        return $this->hasOne(LivroCapa::class, 'livro_codl', 'codl');
    }

    /**
     * Relacionamento com compras (muitos-para-muitos)
     * @return BelongsToMany
     */
    public function compras()
    {
        return $this->belongsToMany(Compra::class, 'compra_livros', 'livro_codl', 'compra_codcompra');
    }
}
