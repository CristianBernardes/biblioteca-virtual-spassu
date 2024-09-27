<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AutorFactory extends Factory
{
    protected $model = \App\Models\Autor::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->name,
        ];
    }
}
