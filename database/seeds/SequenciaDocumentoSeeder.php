<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SequenciaDocumentoSeeder extends Seeder
{
    public function run()
    {
        $sequenciaDocumentos = [
           ['sequencia' => 1, 'empresa_id' => null, 'tipo_documento'=> 'FR','tipoDocumentoNome'=>'FATURA RECIBO', 'serie_documento'=>'AGT'],
           ['sequencia' => 1, 'empresa_id' => null, 'tipo_documento'=> 'FT','tipoDocumentoNome'=>'FATURA','serie_documento'=>'AGT'],
           ['sequencia' => 1, 'empresa_id' => null, 'tipo_documento'=> 'FP','tipoDocumentoNome'=>'FATURA PROFORMA','serie_documento'=>'AGT'],
           ['sequencia' => 1, 'empresa_id' => null, 'tipo_documento'=> 'RC','tipoDocumentoNome'=>'RECIBOS','serie_documento'=>'AGT'],
        ];
        foreach ($sequenciaDocumentos as $sequenciaDocumento){
            DB::connection('mysql2')->table('sequencias_documentos')->insert([
                'sequencia' => $sequenciaDocumento['sequencia'],
                'empresa_id' => $sequenciaDocumento['empresa_id'],
                'tipo_documento' => $sequenciaDocumento['tipo_documento'],
                'serie_documento' => $sequenciaDocumento['serie_documento'],
                'tipoDocumentoNome' => $sequenciaDocumento['tipoDocumentoNome'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
