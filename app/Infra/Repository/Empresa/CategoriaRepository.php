<?php

namespace App\Infra\Repository\Empresa;
use App\Domain\Entity\Empresa\Categoria;
use App\Models\empresa\Categoria as CategoriaDatabase;
use Illuminate\Support\Str;

class CategoriaRepository
{
    public function listarCategoriasSubCategorias($categoriaId){
        return CategoriaDatabase::with('categoriaPai')
            ->where('id', $categoriaId)
            ->first();
    }
    public function getCategorias($search = null)
    {
        return CategoriaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('status_id', 1)
            ->orwhere('empresa_id', null)
            ->search(trim($search))
            ->orderBy('designacao', 'asc')
            ->get();
    }
    public function getCategoriaMaeESubs(){
        $categoriasMae = CategoriaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('categoria_pai', null)
            ->orwhere('empresa_id', null)
            ->orderBy('designacao', 'asc')
            ->get();
        foreach ($categoriasMae as $key=>$categoriaMae){
            $data[$categoriaMae['id']]['id'] = $categoriaMae['id'];
            $data[$categoriaMae['id']]['designacao'] = $categoriaMae['designacao'];
            $data[$categoriaMae['id']]['categoria_pai'] = $categoriaMae['designacao'];
            $subcategoria = CategoriaDatabase::where('categoria_pai', $categoriaMae['id'])->first();
            if($subcategoria){
                $data[$subcategoria['id']]['id'] = $subcategoria['id'];
                $data[$subcategoria['id']]['designacao'] = $subcategoria['designacao'];
                $data[$subcategoria['id']]['categoria_pai'] = $subcategoria['categoria_pai'];
            }
        }
        return $data;
    }
    public function getCategoriasMae()
    {
        return CategoriaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('categoria_pai', null)
            ->orwhere('empresa_id', null)
            ->orderBy('designacao', 'asc')
            ->get();
    }
    public function getSubCategorias($categoriaMaeId){
        if(!$categoriaMaeId) return [];
        return CategoriaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('categoria_pai', $categoriaMaeId)
            ->orderBy('designacao', 'asc')
            ->get();
    }
    public function getCategoria($categoriaId)
    {
        return CategoriaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('id', $categoriaId)
            ->first();
    }


    public function salvar(Categoria $categoria)
    {
        return CategoriaDatabase::create([
            'uuid' => Str::uuid(),
            'designacao' => Str::title($categoria->getDesignacao()),
            'categoria_pai' => $categoria->getCategoriaPai(),
            'status_id' => $categoria->getStatusId(),
            'empresa_id' => auth()->user()->empresa_id,
            'user_id' => auth()->user()->id??35,
            'canal_id' => 2
        ]);
    }
    public function atualizar(Categoria $categoria, $id){
        return CategoriaDatabase::where('id', $id)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->update([
            'designacao' => Str::title($categoria->getDesignacao()),
            'categoria_pai' => $categoria->getCategoriaPai(),
            'status_id' => $categoria->getStatusId(),
        ]);
    }
    public function delete($categoriaId){
        return CategoriaDatabase::where('id', $categoriaId)
            ->where('empresa_id', auth()->user()->empresa_id)->delete();
    }
}
