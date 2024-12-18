<?php

namespace App\Application\UseCase\Empresa\CentrosDeCusto;

use Illuminate\Support\Facades\Storage;

trait TraitUploadFileLogotipoEmpresa
{
    public function uploadFileLogotipo($imagem, $fileAnterior = null)
    {
        $fileDefault = "utilizadores/cliente/centroCusto.jpg";
        if (!$imagem && !$fileAnterior) {
            return $fileDefault;
        };
        if ($fileAnterior && ($fileAnterior !== $fileDefault)) {
            $file = public_path() . "\\upload\\" . $fileAnterior;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        return Storage::disk('public')->putFile('documentos/empresa/documentos', $imagem);
    }

}
