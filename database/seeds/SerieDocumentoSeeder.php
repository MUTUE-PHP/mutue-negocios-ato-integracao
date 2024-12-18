<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SerieDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql2')->table('series_documento')->insert([
            'serie' => 'AGT',
            'empresa_id' => null
        ]);
    }
}
