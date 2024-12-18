<?php

namespace App\Application\UseCase\Empresa\CentrosDeCusto;

use Illuminate\Support\Facades\Storage;

trait TraitUploadFileDocEmpresa
{
    public function uploadFile($imagem, $fileAnterior = null)
    {
        if(!$imagem && !$fileAnterior) return null;

        if ($fileAnterior) {
            $file = public_path() . "\\upload\\" . $fileAnterior;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        return Storage::disk('public')->putFile('documentos/empresa/documentos', $imagem);
    }
}
