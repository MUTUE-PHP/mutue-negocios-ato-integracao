<?php

namespace App\Application\UseCase\Empresa\Proformas;

use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;
use phpseclib\Crypt\RSA as Crypt_RSA;

class ConverterProformaByFaturaRecibo
{
    use TraitChavesEmpresa;

    private FaturaRepository $faturaRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
    }

    public function execute($proforma)
    {
        $fatura = $this->faturaRepository->getFaturaById($proforma['id']);
        $ultimaFatura = $this->faturaRepository->pegarUltimaFactura(1);

        $hashAnterior = "";
        if ($ultimaFatura) {
            $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $ultimaFatura->created_at);
            $hashAnterior = $ultimaFatura->hashValor;
        } else {
            $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        }
        //ManipulaÃ§Ã£o de datas: data da factura e data actual
        //$data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $facturas->created_at);
        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        /*Recupera a sequÃªncia numÃ©rica da Ãºltima factura cadastrada no banco de dados e adiona sempre 1 na sequÃªncia caso o ano da afctura seja igual ao ano actual;
        E reinicia a sequÃªncia numÃ©rica caso se constate que o ano da factura Ã© inferior ao ano actual.*/
        if ($data_factura->diffInYears($datactual) == 0) {
            if ($ultimaFatura) {
                $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $ultimaFatura->created_at);
                $numSequenciaFactura = intval($ultimaFatura->numSequenciaFactura) + 1;
            } else {
                $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $numSequenciaFactura = 1;
            }
        } else if ($data_factura->diffInYears($datactual) > 0) {
            $numSequenciaFactura = 1;
        }

        $getYearNow = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $getYearNow = $getYearNow->execute('ano_de_faturacao');
        $yearNow = Carbon::parse(Carbon::now())->format('Y');

        if ($getYearNow) {
            $yearNow = $getYearNow->valor;
        }


        $numeracaoFactura = "FR ATO" . $yearNow . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);

        $rsa = new Crypt_RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);

        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numeracaoFactura . ';' . number_format($fatura->total, 2, ".", "") . ';' . $hashAnterior;
        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
        $hashValor = base64_encode($signaturePlaintext);
        //Atualizar para proforma convertido
        DB::table('facturas')->where('id', $fatura->id)
            ->update([
                'convertido' => 'Y'
            ]);
        $faturaId = DB::table('facturas')->insertGetId([
            'texto_hash' => $plaintext,
            'codigo_moeda' => $fatura->codigo_moeda,
            'clienteId' => $fatura->clienteId,
            'nome_do_cliente' => $fatura->nome_do_cliente,
            'nomeProprietario' => $fatura->nomeProprietario,
            'telefone_cliente' => $fatura->telefone_cliente,
            'nif_cliente' => $fatura->nif_cliente,
            'email_cliente' => $fatura->email_cliente,
            'endereco_cliente' => $fatura->endereco_cliente,
            'tipo_documento' => 1,
            'numSequenciaFactura' => $numSequenciaFactura,
            'numeracaoFactura' => $numeracaoFactura,
            'numeracaoProforma' => $proforma['numeracaoFactura'],
            'hashValor' => $hashValor,
            'cliente_id' => $fatura->cliente_id,
            'empresa_id' => $fatura->empresa_id,
            'nifEmpresa' => auth()->user()->empresa->nif,
            'nomeEmpresa' => auth()->user()->empresa->nome,
            'centroCustoId' => $fatura->centroCustoId,
            'user_id' => $fatura->user_id,
            'operador' => $fatura->operador,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'paisOrigemId' => $fatura->paisOrigemId,
            'cartaDePorte' => $fatura->cartaDePorte,
            'tipoDeAeronave' => $fatura->tipoDeAeronave,
            'pesoMaximoDescolagem' => $fatura->pesoMaximoDescolagem,
            'dataDeAterragem' => $fatura->dataDeAterragem,
            'dataDeDescolagem' => $fatura->dataDeDescolagem,
            'horaDeAterragem' => $fatura->horaDeAterragem,
            'horaDeDescolagem' => $fatura->horaDeDescolagem,
            'horaEstacionamento' => $fatura->horaEstacionamento,
            'peso' => $fatura->peso,
            'dataEntrada' => $fatura->dataEntrada ?? null,
            'dataSaida' => $fatura->dataSaida ?? null,
            'nDias' => $fatura->nDias,
            'taxaIva' => $fatura->taxaIva,
            'cambioDia' => $fatura->cambioDia,
            'moeda' => $fatura->moeda,
            'horaExtra' => $fatura->horaExtra,
            'contraValor' => $fatura->contraValor,
            'valorIliquido' => $fatura->valorIliquido,
            'valorImposto' => $fatura->valorImposto,
            'total' => $fatura->total,
            'codigoBarra' => $fatura->codigoBarra,
            'tipoDocumento' => 1,
            'formaPagamentoId' => $proforma['formaPagamentoId'],
            'tipoOperacao' => $fatura->tipoOperacao,
            'isencaoIVA' => $fatura->isencaoIVA,
            'convertido' => 'N',
            'anulado' => $fatura->anulado,
            'taxaRetencao' => $fatura->taxaRetencao,
            'valorRetencao' => $fatura->valorRetencao,
            'tipoFatura' => $fatura->tipoFatura,
            'tipoMercadoria' => $fatura->tipoMercadoria,
            'observacao' => $fatura->observacao ?? null
        ]);
        //Gerar o codigo de barra
        DB::table('facturas')->where('id', $faturaId)->update([
            'codigoBarra' => $this->getCodigoBarra($faturaId, $fatura->clienteId)
        ]);
        foreach ($fatura['facturas_items'] as $item) {
            $item = (object)$item;
            DB::table('factura_items')->insert([
                'produtoId' => $item->produtoId,
                'quantidade' => $item->quantidade,
                'nomeProduto' => $item->nomeProduto,
                'taxa' => $item->taxa,
                'taxaLuminosa' => $item->taxaLuminosa,
                'taxaAduaneiro' => $item->taxaAduaneiro,
                'taxaEstacionamento' => $item->taxaEstacionamento,
                'taxaIva' => $item->taxaIva,
                'valorIva' => $item->valorIva,
                'nDias' => $item->nDias,
                'peso' => $item->peso,
                'horaExtra' => $item->horaExtra,
                'taxaAbertoAeroporto' => $item->taxaAbertoAeroporto,
                'horaFechoAeroporto' => $item->horaFechoAeroporto,
                'horaEstacionamento' => $item->horaEstacionamento,
                'sujeitoDespachoId' => $item->sujeitoDespachoId,
                'tipoMercadoriaId' => $item->tipoMercadoriaId,
                'especificacaoMercadoriaId' => $item->especificacaoMercadoriaId,
                'horaAberturaAeroporto' => $item->horaAberturaAeroporto,
                'desconto' => $item->desconto,
                'valorImposto' => $item->valorImposto,
                'dataEntrada' => $item->dataEntrada,
                'dataSaida' => $item->dataSaida,
                'total' => $item->total,
                'totalIva' => $item->totalIva,
                'factura_id' => $faturaId
            ]);
        }
        return $faturaId;
    }

    public function getCodigoBarra($faturaId, $clienteId)
    {
        return "1000" . $clienteId . "" . $faturaId . "" . auth()->user()->id;
    }

}
