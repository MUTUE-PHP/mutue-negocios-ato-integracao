<?php

namespace App\Traits;

use phpseclib\Crypt\RSA;

trait TraitHashDocumento
{
    use TraitChavesEmpresa;
    private $textHash;

    public function gerarHash($numeracaoDocumento, $hashAnterior, $valorDocumento, $dataAtual)
    {
        $rsa = new RSA(); //Algoritimo RSA
        $rsa->loadKey($this->pegarChavePrivada());
        $plaintext = str_replace(date(' H:i:s'), '', $dataAtual) . ';' . str_replace(' ', 'T', $dataAtual) . ';' . $numeracaoDocumento . ';' . number_format($valorDocumento, 2, ".", "") . ';' . $hashAnterior;
        $this->setTextHash($plaintext);
        $hash = 'sha1';
        $rsa->setHash(strtolower($hash));
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);
        $signaturePlaintext = $rsa->sign($plaintext);
        // Lendo a public key
        $rsa->loadKey($this->pegarChavePublica());
        return base64_encode($signaturePlaintext);
    }
    public function setTextHash($textoHash){
        $this->textHash = $textoHash;
    }
    public function getTextHash(){
        return $this->textHash;
    }

}
