<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class BaixarManualController extends Controller
{
    public function imprimirManualUtilizador( HttpRequest $request)
    {

        // $file = public_path('upload/manualUtilizador.pdf');
        // $file ="http://localhost:8000/";
        // return Storage::download($file,'manualUtilizador.pdf');
//PDF file is stored under project/public/download/info.pdf
// $file= public_path(). "/manualUtilizador.pdf";

// $headers = array(
//           'Content-Type: application/pdf',
//         );
//         $response = new BinaryFileResponse($file);
//         $response->headers->set('Content-Type','application/pdf');

//  return response()->download($file,'manualUtilizador.pdf',$headers);

$name = "manualUtilizador.pdf";

    $file = Storage::disk('public')->get("upload/".$name);

    $headers  = array(
        'Content-Type: application/pdf',
    );

   return \Illuminate\Support\Facades\Storage::url("../upload/manualUtilizador.pdf");

    }
}
