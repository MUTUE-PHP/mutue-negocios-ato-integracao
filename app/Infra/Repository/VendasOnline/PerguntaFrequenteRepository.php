<?php

namespace App\Infra\Repository\VendasOnline;

use App\Models\empresa\PerguntaFrequenteVendaOnline;

class PerguntaFrequenteRepository
{

    public function salvar($request)
    {
        return PerguntaFrequenteVendaOnline::create([
            'pergunta' => $request->pergunta,
            'resposta' => $request->resposta,
        ]);
    }
    public function delete($id){
        return PerguntaFrequenteVendaOnline::where('id', $id)->delete();
    }
    public function get(){
        return PerguntaFrequenteVendaOnline::paginate();
    }
    public function atualizar($request){
        return PerguntaFrequenteVendaOnline::where('id', $request->id)->update([
            'pergunta' => $request->perguntaEdit,
            'resposta' => $request->respostaEdit,
        ]);
    }

}
