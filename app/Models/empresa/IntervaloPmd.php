<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class IntervaloPmd extends Model
{
    protected $table = "intervalo_pmd"; 

    protected $fillable = [
        'toneladas_min',
        'toneladas_max',        
        'taxa'
    ];

    public $timestamps = false;
}