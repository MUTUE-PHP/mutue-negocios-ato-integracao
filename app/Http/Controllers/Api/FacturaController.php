<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\empresa\Factura;

class FacturaController extends Controller
{
    public function listarFacturas()
    {
        $centroCustoId = isset($_GET['centroCustoId']) && !empty($_GET['centroCustoId'])?$_GET['centroCustoId']:null;
        $tipoFacturaId = isset($_GET['tipoFacturaId']) && !empty($_GET['tipoFacturaId']) ?$_GET['tipoFacturaId']:null;
        $search = isset($_GET['search']) && !empty($_GET['search'])?$_GET['search']:null;
        $data['facturas'] = Factura::latest()->with(
            [
                'tipoUser', 'tipoDocumento', 'facturas_items',
                'formaPagamento', 'armazem',
                'cliente', 'empresa', 'canal',
                'status', 'user'
            ])->where('empresa_id', auth()->user()->empresa_id)
            ->where(function($query)use($centroCustoId){
                if($centroCustoId){
                    $query->where('centroCustoId', $centroCustoId);
                }else{
                    $query->where('id','>', 0);
                }
            })
            ->where(function($query)use($search){
                if($search){
                    $query->where('numeracaoFactura', $search)
                        ->orwhere('nome_do_cliente', $search)
                        ->orwhere('nif_cliente', $search)
                        ->orwhere(function($query)use($search){
                            $query->whereDate('created_at', '>=', $search)
                                ->whereDate('created_at', '<=', $search);
                    });
                }else{
                    $query->where('id','>', 0);
                }
            })
            ->where(function($query) use($tipoFacturaId){
                if($tipoFacturaId){
                    $query->where('tipo_documento', $tipoFacturaId);
                }else{
                    $query->where('id','>', 0);
                }
            })

            ->paginate(15);
        return response()->json($data, 200);
    }
}
