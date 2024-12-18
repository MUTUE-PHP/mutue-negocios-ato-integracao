<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql2')->table('users_cliente')->insert([
            'id' => 1,
            'name' => 'Boutique da Missão',
            'uuid' => '8aeac39b-aaff-4f2d-9bd4-13222c269589',
            'username' => 'Boutique da Missão',
            'password' => \Illuminate\Support\Facades\Hash::make('mutue123'),
            'status_senha_id' => 2,
            'telefone' => '934983900',
            'email' => 'kuzuata@kimakietu.ao',
            'canal_id' => 3,
            'empresa_id' => 158
        ]);

    }
}
