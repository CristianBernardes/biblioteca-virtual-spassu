<?php

namespace Database\Factories;

use App\Models\Compra;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompraFactory extends Factory
{
    protected $model = Compra::class;

    /**
     * Define o estado padrão do model.
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),  // Associa a compra a um usuário
            'desconto' => $this->faker->numberBetween(0, 20),  // Desconto aleatório entre 0% e 20%
            'valor_compra' => $this->faker->randomFloat(2, 50, 500),  // Valor da compra entre 50 e 500
            'valor_pago' => $this->faker->randomFloat(2, 50, 500),  // Valor da compra entre 50 e 500
            'transacao_sucesso' => $this->faker->boolean(),  // Define se a transação foi bem-sucedida
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
