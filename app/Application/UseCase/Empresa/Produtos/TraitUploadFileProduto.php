<?php

namespace App\Application\UseCase\Empresa\Produtos;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

trait TraitUploadFileProduto
{
    public function uploadFile($imagem, $fileAnterior = null)
    {
        $default = "upload/produtos/default.png";
        if (!$imagem && !$fileAnterior) {
            return $default;
        };
        if ($fileAnterior) {
            $nomeArquivo = $this->pegarNomeArquivo($fileAnterior);
            $path = public_path("upload/produtos/".$nomeArquivo);
            if (file_exists($path) && $fileAnterior !== $default) {
                unlink($path);
            }
        }
        return "upload/" . Storage::disk('public')->putFile('produtos', $imagem);
    }

    private function pegarNomeArquivo($url)
    {
        return preg_replace('~^(https?://[^/]+/[^/]+/[^/]+/)~', '', $url);

    }
    public function eliminarFileProdutoAdicionais($url){

        $nomeArquivo = $this->pegarNomeArquivo($url);
        $path = public_path("upload/produtos/".$nomeArquivo);
        if (file_exists($path)) {
            unlink($path);
        }
    }


}
