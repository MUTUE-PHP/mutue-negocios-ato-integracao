<?php

namespace App\Http\Controllers\empresa\SequenciaDocumentos;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\GetSequenciaDocumento;
use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\GetSequenciaDocumentos;
use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\SalvarSequenciaDocumento;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Repositories\Empresa\ReciboRepository;
use App\Repositories\Empresa\TraitSerieDocumento;
use App\Traits\Empresa\TraitEmpresaAutenticada;
use App\Traits\VerificaSeEmpresaTipoAdmin;
use App\Traits\VerificaSeUsuarioAlterouSenha;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;



class SequenciaDocumentoIndexController extends Component
{

    use VerificaSeEmpresaTipoAdmin;
    use VerificaSeUsuarioAlterouSenha;
    use TraitEmpresaAutenticada;
    use WithPagination;
    use TraitSerieDocumento;
    use LivewireAlert;



    public $recibo;
    public $search;
    public $cart = [];
    public $errors;
    public $documento;
    public $sequenciaDocumentos;


    private $reciboRepository;

    public function boot(ReciboRepository $reciboRepository)
    {
        $this->reciboRepository = $reciboRepository;

    }
    public function mount(){
        $this->sequenciaDocumentos = $this->getSequenciaDocumentos();
    }
    private function isCart($item){
        $cart = collect($this->cart);
        $cart = $cart->firstWhere('tipo_documento', $item['tipo_documento']);
        return $cart;
    }
    public function getSequenciaDocumentos(){
        $getSequenciasDocumentos = new GetSequenciaDocumentos(new DatabaseRepositoryFactory());
        $getSequenciasDocumentos =  $getSequenciasDocumentos->execute($this->search);
        foreach ($getSequenciasDocumentos as $key => $item) {
            $isCart = $this->isCart($item);
            if(!$isCart){
                $this->cart[]= $item;
            }
        }
        return $this->cart;
    }

    public function render()
    {
        return view('empresa.sequenciaDocumentos.index');
    }
    public function salvarSequenciaDocumento(){

        $request = new Request($this->documento);
        try {
            $sequenciaFatura = new SalvarSequenciaDocumento(new DatabaseRepositoryFactory());
            $output = $sequenciaFatura->execute($request);
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => "Ok",
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            sleep(2);
            return redirect()->route("sequenciaDocumento.index");
        }catch (\Exception $e){
            $this->confirm($e->getMessage(), [
                'showConfirmButton' => "Ok",
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
        }
    }
    public function modalSalvarSequencia($sequenciaDocumentoId){
        $getSequenciaDocumento = new GetSequenciaDocumento(new DatabaseRepositoryFactory());
        $sequenciasDocumento =  $getSequenciaDocumento->execute($sequenciaDocumentoId);
        $this->documento['sequencia'] = null;
        $this->documento['tipo_documento'] = $sequenciasDocumento->tipo_documento;
        $this->documento['tipoDocumentoNome'] = $sequenciasDocumento->tipoDocumentoNome;
        $this->documento['serie_documento'] = $this->mostrarSerieDocumento();
    }

}
