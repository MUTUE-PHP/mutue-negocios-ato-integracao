<?php

namespace App\Infra\Repository\Empresa\Relatorios;

use Illuminate\Support\Facades\Response;
use PHPJasper\PHPJasper;

class ReportShowFaturacao
{

    private $formato;
    private $path;


    public function __construct($formato = 'pdf', $path = '/upload/documentos/empresa/modelosFacturas/a4/')
    {
        $this->formato = $formato;
        $this->path = $path;
    }

    public function getDatabaseConfig()
    {
        return [

            'driver' => 'mysql', //mysql, ....
            'username' => env('DB_USERNAME2'),
            'password' => env('DB_PASSWORD2'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE2').'?useSSL=false',
            'port' => '3306'
        ];
    }

    /*
    input => caminho e nome do arquivo gerado no report
    output => caminho e nome do arquivo que serÃ¡ gerado
    params => parametros passados na where do report ['id' => 1]
    options => todas configuraÃ§Ãµes (conexao, format ...)

    */

    public function show($param)
    {
        // instancia um novo objeto JasperPHP
        $report = new PHPJasper();

        $output = public_path() . $this->path . time() . $param['report_file'];

        $input = public_path() . $this->path . $param['report_jrxml'];

        if (count($param['report_parameters'])) {
            $options['params'] = $param['report_parameters'];
        }
        $options['locale'] = 'pt';
        $options['format'] = [$this->formato];

        $options['db_connection'] = $this->getDatabaseConfig();

        $report->process(
            $input,
            $output,
            $options
        )->execute();

        $filename = $output . '.' . $this->formato;
        if (!file_exists($filename)) {
            abort(404);
        }
        $response = Response::make(file_get_contents($filename), 200, [
            'Content-Type' => $this->formato == 'xls' ? 'application/vnd.ms-exce' : 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);

        return [
            'response' => $response,
            'filename' => $filename,
            'file' => $this->path . time() . $param['report_file'] . '.' . $this->formato
        ];
    }

}
