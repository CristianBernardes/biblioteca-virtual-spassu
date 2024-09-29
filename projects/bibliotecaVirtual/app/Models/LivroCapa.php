<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LivroCapa extends Model
{
    use HasFactory;

    /**
     * Especifica o nome da tabela
     * @var string
     */
    protected $table = 'livro_capa';

    /**
     * Especifica o nome da chave primária personalizada
     * @var string
     */
    protected $primaryKey = 'codcapa';

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
        'livro_codl',
        'caminho_imagem'
    ];

    // Relacionamentos

    /**
     * Relacionamento com livro (um-para-um)
     * @return BelongsTo
     */
    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class, 'livro_codl', 'codl');
    }
}
