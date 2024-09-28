<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GlobalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar 5 autores
        $autores = Autor::factory()->count(5)->create();

        // Criar 40 assuntos
        $assuntos = Assunto::factory()->count(40)->create();

        // Criar 10 livros
        $livros = Livro::factory()->count(10)->create();

        // Associar autores aos livros (muitos-para-muitos)
        $livros->each(function ($livro) use ($autores) {
            $livro->autores()->attach(
                $autores->random(2)->pluck('codau')->toArray(), // Seleciona 2 autores aleatórios
                ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()] // Adiciona timestamps
            );
        });

        // Associar assuntos aos livros (muitos-para-muitos)
        $livros->each(function ($livro) use ($assuntos) {
            $livro->assuntos()->attach(
                $assuntos->random(3)->pluck('codas')->toArray(), // Seleciona 3 assuntos aleatórios
                ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()] // Adiciona timestamps
            );
        });

        // Criar dois usuários conhecidos
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

        // Gerar um desconto aleatório entre 0 e 15
        $desconto = rand(0, 15);

        // Simular a associação dos livros comprados com quantidades
        $livrosComprados = $livros->random(3)->map(function ($livro) {
            return ['livro_id' => $livro->codl, 'quantidade' => rand(1, 6)];
        })->toJson();

        // Chamar a procedure para registrar a compra e calcular o valor
        DB::select('CALL ProcessarCompra(?, ?, ?, @compraId)', [$user->id, $desconto, $livrosComprados]);
    }
}
