<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GlobalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criando 5 autores
        $autores = Autor::factory()->count(5)->create();

        // Criando 10 livros sem capa
        $livros = Livro::factory()->count(10)->create();

        // Associando os autores aos livros (muitos-para-muitos)
        $livros->each(function ($livro) use ($autores) {
            $livro->autores()->attach(
                $autores->random(2)->pluck('codau')->toArray()
            );
        });

        // Criando dois usuários com e-mails e senhas conhecidos
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('A1234567'),
            'is_admin' => true
        ]);

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('B9876543'),
            'is_admin' => false
        ]);

        // Gerando um desconto aleatório entre 0 e 15
        $desconto = rand(0, 15);

        // Simulando a associação dos livros comprados com suas quantidades
        $livrosComprados = $livros->random(3)->map(function ($livro) {
            return ['livro_id' => $livro->codl, 'quantidade' => 1];
        })->toJson();

        // Chamando a procedure para registrar a compra e calcular o valor
        DB::select('CALL ProcessarCompra(?, ?, ?, @compraId)', [$user->id, $desconto, $livrosComprados]);
    }
}
