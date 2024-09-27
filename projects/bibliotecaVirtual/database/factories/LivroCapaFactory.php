<?php

namespace Database\Factories;

use App\Models\Livro;
use App\Models\LivroCapa;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivroCapaFactory extends Factory
{
    protected $model = LivroCapa::class;

    /**
     * Define o estado padrão do model.
     */
    public function definition()
    {
        return [
            'livro_codl' => Livro::factory(),  // Associa a capa a um livro
            'caminho_imagem' => $this->faker->imageUrl(),  // Gera um URL de imagem fake
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
