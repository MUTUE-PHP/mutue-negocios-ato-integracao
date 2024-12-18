<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class CouponDesconto extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'coupon_desconto';

    protected $fillable = [
        'id',
        'codigo',
        'percentagem',
        'data_expiracao',
        'used',
        'empresa_id',
        'created_at',
        'updated_at',
    ];

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where("codigo", "like", $term)
                ->orwhere("percentagem", "like", $term);
        });
    }
}
