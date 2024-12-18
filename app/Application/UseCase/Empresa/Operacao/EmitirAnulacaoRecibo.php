<?php

namespace App\Application\UseCase\Empresa\Operacao;

use App\Application\UseCase\Empresa\Faturas\GetAnoDeFaturacao;
use App\Application\UseCase\Empresa\Faturas\GetNumeroSerieDocumento;
use App\Domain\Entity\Empresa\Operacao\AnulacaoDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\NotaCreditoRepository;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;
use phpseclib\Crypt\RSA as Crypt_RSA;

class EmitirAnulacaoRecibo
{
    use TraitChavesEmpresa;

    private NotaCreditoRepository $notaCreditoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->notaCreditoRepository = $repositoryFactory->createNotaCreditoRepository();
    }

    public function execute($input)
    {
//        $input = (object)$input;
//        $getAnoFaturacao = new GetAnoDeFaturacao(new DatabaseRepositoryFactory());
//        $getYearNow = $getAnoFaturacao->execute();
//        $yearNow = Carbon::parse(Carbon::now())->format('Y');
//        if ($getYearNow) {
//            $yearNow = $getYearNow->valor;
//        }
//        $getNumeroSerieDocumento = new GetNumeroSerieDocumento(new DatabaseRepositoryFactory());
//        $numeroSerieDocumento = $getNumeroSerieDocumento->execute();
//        if($numeroSerieDocumento){
//            $numeroSerieDocumento = $numeroSerieDocumento->valor;
//        }else{
//            $numeroSerieDocumento = "ATO";
//        }
//
//        $ultimoDoc = $this->notaCreditoRepository->lastDocument();
//
//        $numSequencia = 1;
//        $hashAnterior = "";
//        if ($ultimoDoc) {
//            $numSequencia = ++$ultimoDoc->numSequencia;
//            $hashAnterior = $ultimoDoc->hash;
//        }
//        $numDoc = 'NC '.$numeroSerieDocumento . $yearNow . '/' . $numSequencia;
//        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
//
//        $rsa = new RSA(); //Algoritimo RSA
//
//        $privatekey = $this->pegarChavePrivada();
//        $rsa->loadKey($privatekey);
//        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numDoc . ';' . number_format($input->totalFatura, 2, ".", "") . ';' . $hashAnterior;
//
//        // HASH
//        $hash = 'sha1'; // Tipo de Hash
//        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
//
//        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
//        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
//        $hashValor = base64_encode($signaturePlaintext);
//
//        $notaCredito = new AnulacaoDocumento(
//            null,
//            $input->reciboId,
//            $numDoc,
//            $hashValor,
//            $plaintext,
//            $numSequencia,
//            $input->descricao
//        );
//        $notaCredito = $this->notaCreditoRepository->salvar($notaCredito);
//        //Atualizar status da fatura para anulado;
        $notaCredito = DB::table('recibos')->where('id', $input['reciboId'])->update([
            'anulado' => 'Y'
        ]);
        return $notaCredito;
    }

}
