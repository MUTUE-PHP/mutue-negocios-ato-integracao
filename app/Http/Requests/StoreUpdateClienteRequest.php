<?php

namespace App\Http\Requests;


trait StoreUpdateClienteRequest
{


    public function rules()
    {
        return [
            'cliente.nome' => 'required',
            'cliente.pais_id' => 'required',
            'cliente.tipo_cliente_id' => 'required',
            'cliente.pessoa_contacto' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'cliente.nome.required' => 'Informe o nome do cliente',
            'cliente.tipo_cliente_id.required' => 'Informe o tipo cliente',
        ];
    }
}
