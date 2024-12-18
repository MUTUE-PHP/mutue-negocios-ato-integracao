<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CoordenadaBancariaAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coordenadaBancarias = [
            [
                'uuid' => Str::uuid(),
                'num_conta' => '03179526626',
                'iban' => 'AO06 0045.0951.0317.9526.6262.8',
                'banco_id' => 1,
                'canal_id' => 3,
                'status_id' => 1,
                'user_id' => 1,
            ],
            [
                'uuid' => Str::uuid(),
                'num_conta' => '03179526620',
                'iban' => 'AO06 0045.0010.0317.0102.1010.6',
                'banco_id' => 2,
                'canal_id' => 3,
                'status_id' => 1,
                'user_id' => 1,
            ]
        ];

        foreach ($coordenadaBancarias as $coordenadaBancaria) {
            $coordenadaBancaria = (object)$coordenadaBancaria;
            DB::connection('mysql')->table('coordenadas_bancarias')->insert([
                'num_conta' => $coordenadaBancaria->num_conta,
                'iban' => $coordenadaBancaria->iban,
                'banco_id' => $coordenadaBancaria->banco_id,
                'canal_id' => $coordenadaBancaria->canal_id,
                'status_id' => $coordenadaBancaria->status_id,
                'user_id' => $coordenadaBancaria->user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
