<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\DB;

trait UpdateBancoRequest
{

    public function rules()
    {
        return [
            'banco.designacao' => ["required"],
            "banco.sigla" => "required",
            "banco.iban" => "required",
            "banco.swift" => "",
            "banco.status_id" => "required",

        ];
    }
    public function messages()
    {
        return [
            'banco.designacao.required' => 'campo obrigatório',
            'banco.sigla.required' => 'campo obrigatório',
            'banco.iban.required' => 'campo obrigatório',
        ];
    }
}
