<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compra extends Model
{
    use HasFactory;

    /**
     * Especifica o nome da tabela
     * @var string
     */
    protected $table = 'compras';

    /**
     * Especifica o nome da chave primária personalizada
     * @var string
     */
    protected $primaryKey = 'codcompra';

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
        'user_id',
        'valor_compra',
        'desconto',
        'transacao_sucesso'
    ];

    // Relacionamentos

    /**
     * Relacionamento com livros (muitos-para-muitos)
     * @return BelongsToMany
     */
    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'compra_livros', 'compra_codcompra', 'livro_codl');
    }

    /**
     * Relacionamento com usuário (muitos-para-um)
     * @return BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
