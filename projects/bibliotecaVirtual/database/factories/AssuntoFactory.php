<?php

namespace Database\Factories;

use App\Models\Assunto;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssuntoFactory extends Factory
{
    protected $model = Assunto::class;

    /**
     * Define o estado padrão do model.
     */
    public function definition()
    {
        return [
            'descricao' => $this->faker->unique()->word(),  // Gerando uma descrição com 1 palavra
        ];
    }
}
