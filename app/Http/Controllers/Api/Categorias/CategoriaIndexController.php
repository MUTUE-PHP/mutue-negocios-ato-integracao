<?php

namespace App\Http\Controllers\Api\Categorias;
use App\Http\Controllers\Controller;
use App\Models\empresa\Categoria;
use App\Repositories\Empresa\CategoriaRepository;
use Illuminate\Support\Facades\DB;

class CategoriaIndexController extends Controller
{
    private $categoriaRepository;

    public function __construct(CategoriaRepository $categoriaRepository )
    {
        $this->categoriaRepository = $categoriaRepository;
    }
    public function mv_listarCategoriasSemPaginacao($search = null){
        $empresasIds = DB::connection('mysql2')->table('empresas')->where('venda_online', 'Y')
            ->where('id', 148)
            ->pluck('id');
        $categorias = Categoria::with('subcategorias')
            ->withCount('produtos')
            ->where('categoria_pai', null)
            ->where('status_id', 1)
            ->whereIn('empresa_id', $empresasIds)
            ->orderby('designacao', 'asc')
            ->get();


        return response()->json($categorias);

//        $categorias = DB::connection('mysql2')->table('categorias')->whereIn('id', $categoriaIds)
//            ->get();
        return $categorias;
//        return $this->categoriaRepository->mv_listarCategoriasSemPaginacao($search);
    }
}
