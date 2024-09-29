<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LivroFactory extends Factory
{
    protected $model = \App\Models\Livro::class;

    public function definition(): array
    {
        return [
            'titulo' => $this->faker->text(12),
            'editora' => $this->faker->company,
            'edicao' => $this->faker->numberBetween(1, 10),
            'ano_publicacao' => $this->faker->year,
            'valor' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
