<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chama o GlobalSeeder para executar as seeds globais
        $this->call(GlobalSeeder::class);
    }
}
