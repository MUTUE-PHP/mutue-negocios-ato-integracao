<?php

namespace App\Application\UseCase\Empresa\Inventarios;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\InventarioRepository;
use App\Models\empresa\AtualizacaoStocks;
use App\Models\empresa\ExistenciaStock;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;
use phpseclib\Crypt\RSA as Crypt_RSA;

class EmitirInventario
{
    use TraitChavesEmpresa;

    private InventarioRepository $inventarioRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->inventarioRepository = $repositoryFactory->createInventarioRepository();
    }

    public function execute($produtos)
    {
        $lastInventario = $this->inventarioRepository->getUltimoInventario();
        $hashAnterior = null;
        if ($lastInventario) {
            $data_inventario = Carbon::createFromFormat('Y-m-d H:i:s', $lastInventario->created_at);
            $hashAnterior = $lastInventario->hash;
        } else {
            $data_inventario = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        }
        //$data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $facturas->created_at);
        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        /*Recupera a sequência numérica da última factura cadastrada no banco de dados e adiona sempre 1 na sequência caso o ano da afctura seja igual ao ano actual;
        E reinicia a sequência numérica caso se constate que o ano da factura é inferior ao ano actual.*/
        if ($data_inventario->diffInYears($datactual) == 0) {
            if ($lastInventario) {
                $data_inventario = Carbon::createFromFormat('Y-m-d H:i:s', $lastInventario->created_at);
                $numSequenciaInventario = intval($lastInventario->numSequenciaInventario) + 1;
            } else {
                $data_inventario = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $numSequenciaInventario = 1;
            }
        } else if ($data_inventario->diffInYears($datactual) > 0) {
            $numSequenciaInventario = 1;
        }
        $numeracaoInventario = 'IV ' . mb_strtoupper(substr(auth()->user()->empresa->nome, 0, 3) . '' . date('Y')) . '/' . $numSequenciaInventario; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);

        $rsa = new Crypt_RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);

        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numeracaoInventario . ';' . $hashAnterior;

        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

        // Lendo a public key
        $rsa->loadKey($publickey);

        $inventarioId = DB::connection('mysql2')->table('inventarios')->insertGetId([
            'empresa_id' => auth()->user()->empresa_id,
            'data_inventario' => Carbon::now()->format('Y-m-d H:m:s'),
            'user_id' => auth()->user()->id,
            'tipo_user_id' => 2, //empresa,
            'canal_id' => 2,
            'status_id' => 1,
            'armazem_id' => $produtos[0]['armazem_id'],
            'observacao' => null,
            'numSequenciaInventario' => $numSequenciaInventario,
            'numeracao' => $numeracaoInventario,
            'hash' => base64_encode($signaturePlaintext),
        ]);

        foreach ($produtos as $key => $item) {
            if (empty($item['quantidadeAtual']) || is_null($item['quantidadeAtual']) || !is_numeric($item['quantidadeAtual'])) {
                $quantidadeAtual = null;
            } else {
                $quantidadeAtual = $item['quantidadeAtual'];
            }
            $quantidadeAnterior = !isset($item['quantidade']) ? 0 : $item['quantidade'];
            $diffAtual = $quantidadeAtual == null ? $quantidadeAnterior : $quantidadeAtual;
            $diferenca = abs($quantidadeAnterior - $diffAtual);

            DB::connection('mysql2')->table('inventario_itens')->insertGetId([
                'inventario_id' => $inventarioId,
                'produto_id' => $item['produto_id'],
                'existenciaAnterior' => (int)$quantidadeAnterior,
                'existenciaActual' => $quantidadeAtual == null ? $quantidadeAnterior : $quantidadeAtual,
                'precoVenda' => $item['preco_venda'],
                'precoCompra' => $item['preco_compra'],
                'diferenca' => (int)$diferenca,
                'actualizou' => "Sim",
            ]);

            $this->actualizarQtExistenciaStock($item, $produtos[0]['armazem_id']);
            $this->actualizaStock($item,$produtos[0]['armazem_id']);

        }
        return $inventarioId;
    }

    public function actualizarQtExistenciaStock($item, $armazemId)
    {

        DB::connection('mysql2')->table('existencias_stocks')->where('produto_id', $item['produto_id'])
            ->where('armazem_id', $armazemId)
            ->where('empresa_id', auth()->user()->empresa_id)->update([
                'quantidade' => $item['quantidadeAtual'],
                'observacao' =>  "Inventario, actualiza stock para quantidade " . $item['quantidadeAtual'],
            ]);
    }

    public function actualizaStock($item, $armazemId)
    {
        DB::connection('mysql2')->table('actualizacao_stocks')->where('produto_id', $item['produto_id'])
            ->where('armazem_id', $armazemId)
            ->where('empresa_id', auth()->user()->empresa_id)->update([
                'quantidade_nova' =>$item['quantidadeAtual'],
                'quantidade_antes' =>$item['quantidade'],
                'descricao' =>"Inventario, actualiza stock para quantidade " . $item['quantidadeAtual'],
            ]);
    }

}
