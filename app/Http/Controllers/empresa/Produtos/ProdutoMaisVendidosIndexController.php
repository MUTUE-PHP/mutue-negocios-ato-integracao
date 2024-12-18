<?php
namespace App\Http\Controllers\empresa\Produtos;
use App\Application\UseCase\Empresa\Produtos\GetProdutosMaisVendidos;
use App\Application\UseCase\Empresa\Produtos\GetProdutosMaisVendidosTenancy;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Livewire\Component;
class ProdutoMaisVendidosIndexController extends Component
{
    public $search = null;
    public function render(){
        $getProdutosMaisVendidos = new GetProdutosMaisVendidosTenancy(new DatabaseRepositoryFactory());
        $data['produtos'] = $getProdutosMaisVendidos->execute($this->search);
        return view('empresa.produtos.listarMaisVendidos', $data);
    }
    public function imprimirProdutosMaisVendidos(){

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;

        $filename = "produtosMaisVendidos";

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'diretorio' => $logotipo,
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
}?>
