<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assunto extends Model
{
    use HasFactory;

    /**
     * Especifica o nome da tabela
     * @var string
     */
    protected $table = 'assunto';

    /**
     * Especifica o nome da chave primária personalizada
     * @var string
     */
    protected $primaryKey = 'codas';

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
        'descricao'
    ];

    // Relacionamentos

    /**
     * Relacionamento com livros (muitos-para-muitos)
     * @return BelongsToMany
     */
    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'livro_assunto', 'assunto_codas', 'livro_codl');
    }
}
