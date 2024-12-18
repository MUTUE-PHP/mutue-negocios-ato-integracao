<?php

use Illuminate\Database\Seeder;

class TipoClienteEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tiposClientes = [
            'Singular',
            'Instituição Privada',
            'Instituição Publica',
            'Sociedade Anónima',
            'ONG',
            'Diversos',
        ];
        foreach ($tiposClientes as $designacao){
            \Illuminate\Support\Facades\DB::connection('mysql2')->table('tipos_clientes')->insert([
                'designacao' => $designacao,
            ]);
        }
    }
}
