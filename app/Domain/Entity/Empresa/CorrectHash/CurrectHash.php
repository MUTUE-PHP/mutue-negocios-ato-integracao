<?php

namespace App\Domain\Entity\Empresa\CorrectHash;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;

class CurrectHash
{
    use TraitChavesEmpresa;
    private $typeDocument;
    private $tableName;
    private $tenancy = false;

    public function __construct($typeDocument, $tableName, $tenancy = false)
    {
        $this->typeDocument = $typeDocument;
        $this->tableName = $tableName;
    }
    public function execute()
    {
        $rsa = new RSA(); //Algoritimo RSA
        $privatekey = $this->pegarChavePrivada();
        foreach ($this->getDocuments() as $document) {
            $numberInvoice = $document->numeracaoFactura;
            $numSequencia = --$document->numSequenciaFactura;
            $yearInvoice = $this->getYearInvoice($numberInvoice);
            $serieDocument = $this->getSerieDocument($numberInvoice, $yearInvoice);
            $lastDocument = $this->getLastDocumentInvoice($yearInvoice, $serieDocument, $numSequencia);

            $hashAnterior = null;
            if ($lastDocument) {
                $hashAnterior = $lastDocument->hashValor;
            }
            $rsa->loadKey($privatekey);
            $dataCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $document->created_at);
            $plaintext = $dataCarbon->format('Y-m-d') . ';' . str_replace(' ', 'T', $document->created_at) . ';' . str_replace("FP", "PP", $numberInvoice) . ';' . number_format(($document->valorIliquido + $document->valorImposto), 2, ".", "") . ';' . $hashAnterior;

           // dd($plaintext);
            // HASHD
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
            $hashValor = base64_encode($signaturePlaintext);

            DB::table($this->getTableName())
                ->where('id', $document->id)
                ->update([
                'texto_hash' => $plaintext,
                'hashValor' => $hashValor,
                'created_at' => $document->created_at,
                'updated_at' => $document->updated_at
            ]);
        }
    }
    public function getYearInvoice($string)
    {
        // Divide a string pelo caractere '/'
        $partes = explode('/', $string);
        // A primeira parte contém o ano
        $parte_com_ano = $partes[0];
        // Remove os caracteres não numéricos
        $ano = preg_replace('/[^0-9]/', '', $parte_com_ano);
        return $ano;
    }
    public function getSerieDocument($string, $yearInvoice)
    {
        $string = explode($yearInvoice, $string);
        $partes = explode(" ", $string[0]);
        return $partes[1];
    }

    public function getLastDocumentInvoice($yearInvoice, $serieDocument, $numSequencia)
    {
        $empresaId = 1;
        $resultados = DB::connection('mysql2')->select("SELECT *
          FROM facturas
          WHERE empresa_id = " . $empresaId . " and  SUBSTRING_INDEX(numeracaoFactura, '/', 1) LIKE '%" . $yearInvoice . "%' and numeracaoFactura  LIKE '%" . $serieDocument . "%'
            AND numeracaoFactura LIKE '%" . $this->getTypeDocument() . "%' AND numSequenciaFactura = $numSequencia
          ORDER BY created_at DESC
          LIMIT 1");
        if (!$resultados) return null;
        return json_decode(json_encode($resultados[0]));
    }
    public function getDocuments()
    {
        return DB::table($this->tableName)
            ->where(function ($query) {
                if ($this->isTenancy()) {
                    $query->where('empresaId', auth()->user()->empresaId);
                }
            })->where(function ($query) {
                $typeDocument = "%{$this->getTypeDocument()}%";
                $query->where('numeracaoFactura', 'like', $typeDocument);
            })->orderBy('created_at', 'asc')->get();
    }
    public function getTypeDocument()
    {
        return $this->typeDocument;
    }
    public function getTableName()
    {
        return $this->tableName;
    }
    public function isTenancy(): bool
    {
        return $this->tenancy;
    }
}
