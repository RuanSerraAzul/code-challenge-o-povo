<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Jornalista::create([
            'nome' => 'Usuário',
            'sobrenome' => 'de Teste Cardoso',
            'email' => 'usuario@teste.com.br',
            'password' => bcrypt('senha123'),
        ]);
    }
}
