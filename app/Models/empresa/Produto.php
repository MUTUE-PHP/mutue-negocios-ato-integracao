<?php

namespace App\Models\empresa;

use App\Models\Portal\CarrinhoProduto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Keygen\Keygen;

class Produto extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'produtos';
    protected $primaryKey = "id";
    protected $guarded = ['id'];
    protected $userId;

    protected $fillable = [
        'id',
        'designacao',
        'uuid',
        'preco_venda',
        'pvp',
        'preco_compra',
        'classificacao',
        'marca_id',
        'categoria_id',
        'tipoServicoId',
        'orderCategoria1',
        'orderCategoria2',
        'orderCategoria3',
        'classe_id',
        'unidade_medida_id',
        'fabricante_id',
        'user_id',
        'canal_id',
        'status_id',
        'codigo_taxa',
        'motivo_isencao_id',
        'quantidade_minima',
        'quantidade_critica',
        'imagem_produto',
        'referencia',
        'codigo_barra',
        'data_expiracao',
        'descricao',
        'stocavel',
        'venda_online',
        'cartaGarantia',
        'tempoGarantiaProduto',
        'tipoGarantia',
        'centroCustoId',
        'empresa_id',
    ];

    public static function boot($length = 6)
    {
        parent::boot();
        if(empty($model->referencia)){
           $codigo = mb_strtoupper(Keygen::alphanum($length)->generate());
           $codigoExiste = DB::connection('mysql2')->table('produtos')
               ->where('empresa_id', auth()->user()->empresa_id??53)
               ->where('referencia', $codigo)
               ->first();
           if ($codigoExiste) {
               self::boot($length++);
           } else {
               self::creating(function ($model) use ($codigo) {
                   if(empty($model->referencia)){
                       $model->referencia = $codigo;
                   }else{
                       $model->referencia = $model->referencia;
                   }
               });
           }
       }
    }
    public function carateristicas()
    {
        return $this->hasMany(Categoriacaracteristicas::class, 'produto_id');
    }
    public function valorCaracteristica()
    {
        return $this->belongsToMany(ValorCaracteristica::class, 'valorcaracteristicas_produtos')
            ->withPivot(['produto_id']);
    }

    public function statuGeral()
    {
        return $this->belongsTo(Statu::class, 'status_id');
    }
    public function tipoTaxa()
    {
        return $this->belongsTo(TipoTaxa::class, 'codigo_taxa', 'codigo');
    }
    public function userId($userId = null)
    {
        $this->userId = $userId;
    }
    public function favorito()
    {
        return $this->hasMany(ProdutoFavorito::class, 'produto_id')->where('user_id', session('USER_FAVORITO_ID'));
    }

    public function unidade()
    {
        return $this->belongsTo(UnidadeMedida::class, 'unidade_medida_id');
    }
    public function unidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'unidade_medida_id');
    }
    public function existenciaEstock()
    {
        return $this->hasMany(ExistenciaStock::class, 'produto_id', 'id');
    }
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    public function tipoServico()
    {
        return $this->belongsTo(TipoServico::class,'tipoServicoId', 'id');
    }
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
    public function fabricante()
    {
        return $this->belongsTo(Fabricante::class, 'fabricante_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function canal()
    {
        return $this->belongsTo(CanalComunicacao::class, 'canal_id');
    }
    public function status()
    {
        return $this->belongsTo(Statu::class, 'status_id');
    }
    public function motivoIsencao()
    {
        return $this->belongsTo(TipoMotivoIva::class, 'motivo_isencao_id');
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa_Cliente::class, 'empresa_id');
    }

    public function classificacao()
    {
        return $this->hasMany(Classificacao::class);
    }

    public function produtoImagens()
    {
        return $this->hasMany(ProdutoImagem::class, 'produto_id', 'id');
    }

    public function scopeFilter($query, $term)
    {
        $search = trim($term['search']) !== "" ? trim($term['search']) : null;
        $vendasOnline = $term['vendasOnline'] !== "" ? $term['vendasOnline'] : null;
        $centroCustoId = $term['centroCustoId'] !== "" ? $term['centroCustoId'] : null;

        return $query->where(function ($query) use ($search, $vendasOnline, $centroCustoId) {
            if($vendasOnline){
                $query->where('venda_online', $vendasOnline);
            }
            if($centroCustoId){
                $query->where('centroCustoId', $centroCustoId);
            }
            if($search){
                $query->where('designacao','like', $search . '%')
                ->orwhere('referencia','like', $search . '%')
                ->orwhere('codigo_barra','like', $search . '%');
            }
        });
    }
    public function scopeCheckAuth($query, $userId){
        $this->userId = $userId;
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->whereHas('categoria', function ($query) use ($term) {
            $query->where('designacao', 'like', $term);
        })->orwhereHas('marca', function ($query) use ($term) {
            $query->where('designacao', 'like', $term);
        })->orwhere(function ($query) use ($term) {
            $query->where("designacao", "like", $term)
                ->orwhere("preco_venda", "like", $term)
                ->orwhere("preco_compra", "like", $term)
                ->orwhere("referencia", "like", $term)
                ->orwhere("codigo_barra", "like", $term);
        });
    }
    public function scopeOrderByFilter($query, $term){
        if(!$term) return $query->where('id','>', 0);

        if($term == 'min'){
            return $query->orderBy('preco_venda', 'asc');
        }else if($term == 'max'){
            return $query->orderBy('preco_venda', 'desc');
        }else if($term == 'desc'){
            return $query->orderBy('designacao', 'desc');
        }else if($term == 'asc'){
            return $query->orderBy('designacao', 'asc');
        }
    }

    public function scopeVendaOnline($query, $term)
    {

        $query->where(function ($query) use ($term) {
            if ($term == 'N') {
                $query->where("id", '>', 0);
            }
            $query->where("venda_online", $term);
        });
    }
}
