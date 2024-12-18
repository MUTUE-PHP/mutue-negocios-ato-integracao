@php use Illuminate\Support\Str; @endphp
@section('title','Emissão de faturas')
<div class="row">
    <div id="main-container">
        <div class="main-content">
            <div class="main-content-inner">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="space-6"></div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget-box transparent">
                                    <div class="widget-header widget-header-large">
                                        <div class="widget-toolbar no-border invoice-info" wire:ignore>
                                            <span class="invoice-info-label">Data:</span>
                                            <span class="blue" id="contador"></span>
                                        </div>
                                        <h5>SERVIÇOS COMERCIAIS</h5>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-24">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div
                                                            class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                                            <b>DADOS DA EMPRESA</b>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <ul class="list-unstyled spaced">
                                                            <li>
                                                                <h4>{{ $empresa->nome }}</h4>
                                                            </li>

                                                            <li>
                                                                <i class="ace-icon fa fa-caret-right blue"></i>
                                                                <strong>NIF: </strong>{{ $empresa->nif }}
                                                            </li>

                                                            <li>
                                                                <i class="ace-icon fa fa-caret-right blue"></i>
                                                                <strong>Endereço: </strong>{{ $empresa->endereco }}
                                                            </li>

                                                            <li>
                                                                <i class="ace-icon fa fa-caret-right blue"></i>
                                                                <strong>Telefone: </strong>{{ $empresa->pessoal_Contacto }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div><!-- /.col -->

                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div
                                                            class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                                            <b>DADOS DO CLIENTE</b>
                                                        </div>
                                                    </div>
                                                    <div>

                                                        <ul class="list-unstyled  spaced">
                                                            <div style="margin-top: 10px; margin-bottom: 15px;">
                                                                <li>
                                                                    <select wire:model="fatura.clienteId"
                                                                            data="clienteId"
                                                                            class="col-md-12 select2"
                                                                            id="clienteId"
                                                                            style="height:35px;">
                                                                        <option value="">Seleciona o cliente</option>
                                                                        @foreach($clientes as $cliente)
                                                                            <option
                                                                                value="{{ $cliente->id }}">{{Str::upper($cliente->nome)}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('fatura.clienteId'))
                                                                        <span style="color: red; font-weight: bold">
                                                                            <strong>{{ $errors->first('fatura.clienteId') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </li>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Observação</label>
                                                                <div>
                                                                    <input type="text" style="width: 100%" wire:model="fatura.observacao" placeholder="Observação..."/>
                                                                </div>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                            <div class="space"></div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="widget-box">
                                                        <div class="widget-header">
                                                        </div>

                                                        <div class="widget-body">
                                                            <div class="widget-main" style="margin-bottom: 10px">
                                                                <form class="form-inline">
                                                                    <div class="form-group"
                                                                         style="margin-right: 15px;margin-bottom: 10px">
                                                                        <label>Unidade</label>
                                                                        <div>
                                                                            <input type="number" id="money"
                                                                                   wire:model="fatura.unidadeMetrica"
                                                                                   value="0,00"
                                                                                   name="money"
                                                                                   class="input-small"
                                                                                   style="width: 150px; <?= $errors->has('fatura.unidadeMetrica') ? 'border-color: #ff9292;' : '' ?>"/>
                                                                            @if ($errors->has('fatura.unidadeMetrica'))
                                                                                <span class="help-block"
                                                                                      style="color: red; font-weight: bold">
                                                                                    <strong>{{ $errors->first('fatura.unidadeMetrica') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group"
                                                                         style="margin-right: 15px;margin-bottom: 10px">
                                                                        <label>Qtd. Mês</label>
                                                                        <div>
                                                                            <input type="number" id="money"
                                                                                   wire:model="fatura.qtdMeses"
                                                                                   value="0,00"
                                                                                   name="money"
                                                                                   class="input-small"
                                                                                   style="width: 80px; <?= $errors->has('fatura.qtdMeses') ? 'border-color: #ff9292;' : '' ?>"/>
                                                                            @if ($errors->has('fatura.qtdMeses'))
                                                                                <span class="help-block"
                                                                                      style="color: red; font-weight: bold">
                                                                                    <strong>{{ $errors->first('fatura.qtdMeses') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-bottom: 10px">
                                                                        <label>Data/Hora de entrada</label>
                                                                        <div>
                                                                            <input type="datetime-local" wire:model="fatura.dataEntradaEstacionamento" class="input-small" style="width: 200px; <?= $errors->has('fatura.dataEntradaEstacionamento') ? 'border-color: #ff9292;' : '' ?>"/>
                                                                            @if ($errors->has('fatura.dataEntradaEstacionamento'))
                                                                                <span class="help-block"
                                                                                      style="color: red; font-weight: bold">
                                                                                    <strong>{{ $errors->first('fatura.dataEntradaEstacionamento') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group"
                                                                         style="margin-right: 15px;margin-bottom: 10px">
                                                                        <label>Data/Hora de saída</label>
                                                                        <div>
                                                                            <input type="datetime-local" wire:model="fatura.dataSaidaEstacionamento" class="input-small" style="width: 200px; <?= $errors->has('fatura.dataSaidaEstacionamento') ? 'border-color: #ff9292;' : '' ?>"/>
                                                                            @if ($errors->has('fatura.dataSaidaEstacionamento'))
                                                                                <span class="help-block"
                                                                                      style="color: red; font-weight: bold">
                                                                                    <strong>{{ $errors->first('fatura.dataSaidaEstacionamento') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-bottom: 10px">
                                                                        <label>Tipo Documento</label>
                                                                        <div>
                                                                            <select style="width: 150px;height: 34px"
                                                                                    wire:model="fatura.tipoDocumento"
                                                                                    name="ship"
                                                                                    rowid="6"
                                                                                    size="1"
                                                                                    class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                                <option value="1">Factura Recibo
                                                                                </option>
                                                                                <option value="2">Factura</option>
                                                                                <option value="3">Factura Proforma
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group" style="margin-bottom: 10px">
                                                                        <label>Forma Pagamento</label>
                                                                        <div>
                                                                            <select style="width: 200px;height: 34px"
                                                                                    wire:model="fatura.formaPagamentoId"
                                                                                    name="ship"
                                                                                    rowid="6"
                                                                                    size="1"
                                                                                    class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                                @foreach($formasPagamentos as $formaPagamento)
                                                                                    <option value="{{ $formaPagamento->id }}" <?= $formaPagamento['id'] == $fatura['formaPagamentoId']?'selected':''?>>{{ $formaPagamento->descricao }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-bottom: 10px">
                                                                        <label>Moeda</label>
                                                                        <div>
                                                                            <select style="width: 80px;height: 34px"
                                                                                    wire:model="fatura.moedaPagamento"
                                                                                    name="ship"
                                                                                    rowid="6"
                                                                                    size="1"
                                                                                    class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                                @foreach($moedas as $moeda)
                                                                                    <option value="{{ $moeda['designacao'] }}" <?= $moeda['designacao'] == $fatura['moedaPagamento']?'selected':''?>>{{ $moeda['designacao'] }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-left: 15px;margin-bottom: 10px">
                                                                        <label for="addArCondicionado">Arcondicionado</label>
                                                                        <div>
                                                                            <input name="form-field-checkbox" wire:model="fatura.addArCondicionado" id="addArCondicionado" type="checkbox"
                                                                                   class="ace input-lg"/>
                                                                            <span class="lbl bigger-140"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-left: 15px;margin-bottom: 10px">
                                                                        <label for="isencaoOcupacao">Isenção/Ocupação</label>
                                                                        <div>
                                                                            <input name="form-field-checkbox" wire:model="fatura.isencaoOcupacao" id="isencaoOcupacao" type="checkbox"
                                                                                   class="ace input-lg"/>
                                                                            <span class="lbl bigger-140"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-left: 15px;margin-bottom: 10px">
                                                                        <label for="isencaoIva">Isenção/IVA</label>
                                                                        <div>
                                                                            <input name="form-field-checkbox" wire:model="fatura.isencaoIVA" id="isencaoIva" type="checkbox"
                                                                                   class="ace input-lg"/>
                                                                            <span class="lbl bigger-140"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-left: 15px;margin-bottom: 10px">
                                                                        <label for="retencao">Incluir/Retenção</label>
                                                                        <div>
                                                                            <input name="form-field-checkbox" wire:model="fatura.retencao" id="retencao" type="checkbox"
                                                                                   class="ace input-lg"/>
                                                                            <span class="lbl bigger-140"></span>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div>

                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th style="width: 400px; text-align: left">Tarifas</th>
                                                                <th style="text-align: left">Unid. métrica</th>
                                                                <th style="width: 60px; text-align: left">Ar condicionado</th>
                                                                <th style="text-align: left">Qtd.Mês</th>
                                                                <th>Desc(%)</th>
                                                                <th>NºHora/min</th>
                                                                <th style="text-align: right">Total</th>
                                                                <th style="text-align: center"></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($fatura['items'] as $key=> $faturaItem)
                                                                <tr>
                                                                    <td>{{++$key}}</td>
                                                                    <td style="width: 400px; text-align: left">{{ $faturaItem['nomeProduto'] }}</td>
                                                                    <td>{{ number_format($faturaItem['unidadeMetrica'],2,',','.') }}</td>
                                                                    <td>{{ $faturaItem['addArCondicionado']?'Sim': 'Não'}}</td>
                                                                    <td>{{ number_format($faturaItem['qtdMeses'],1,',','.') }}</td>
                                                                    <td>{{ number_format($faturaItem['desconto'], 1, ',', '.') }}</td>
                                                                    <td>{{ $faturaItem['descHoraEstacionamento'] }}</td>
                                                                    <td style="text-align: right">{{ number_format($faturaItem['total'],2,',','.') }}</td>
                                                                    <td style="text-align: center">
                                                                            <div
                                                                                class="hidden-sm hidden-xs btn-group">
                                                                                <button
                                                                                    class="btn btn-xs btn-danger"
                                                                                    wire:click.prevent="removeCart({{json_encode($faturaItem) }})">
                                                                                    <i class="ace-icon fa fa-remove bigger-120"></i>
                                                                                </button>
                                                                            </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div>
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>Tarifas</th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="center">
                                                                    <select style="width: 100%"
                                                                            wire:model="item.produto" name="ship"
                                                                            rowid="6"
                                                                            size="1"
                                                                            class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                        <option value="">Nenhum</option>
                                                                        @foreach($servicos as $servico)
                                                                            <option
                                                                                value="{{json_encode($servico->produto)}}">{{ $servico->produto->designacao }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="hidden-sm hidden-xs btn-group">
                                                                        <button class="btn btn-xs btn-success"
                                                                                wire:click.prevent="addCart">
                                                                            <i class="ace-icon fa fa-plus bigger-120"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hr hr8 hr-double hr-dotted"></div>

                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        VALOR LIQUIDO(AOA) :
                                                        <span>{{ number_format($fatura['valorliquido'], 2,',','.') }}Kz</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        VALOR DESCONTO(AOA) :
                                                        <span>{{ number_format($fatura['valorDesconto'], 2,',','.') }}Kz</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        VALOR ILIQUIDO(AOA) :
                                                        <span>{{ number_format($fatura['valorIliquido'], 2,',','.') }}Kz</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        VALOR DO IMPOSTO(AOA) :
                                                        <span>{{ number_format($fatura['valorImposto'], 2,',','.') }}Kz</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        VALOR DO IMPOSTO PREDIAL(AOA) :
                                                        <span>{{ number_format($fatura['valorImpostoPredial'], 2,',','.') }}Kz</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        RETENÇÃO(%) :
                                                        <span>{{ number_format($fatura['taxaRetencao'], 2,',','.') }}%</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        VALOR DA RETENÇÃO(AOA) :
                                                        <span>{{ number_format($fatura['valorRetencao'], 2,',','.') }}Kz</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        TOTAL(AOA) :
                                                        <span><strong>{{ number_format($fatura['total'], 2,',','.') }}Kz</strong></span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 5px">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        TAXA DE CÂMBIO(AOA/USD) :
                                                        <span>{{ number_format($fatura['cambioDia'], 2,',','.') }}</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        CONTRAVALOR(USD) :
                                                        <span><strong>${{ number_format($fatura['contraValor'], 2,',','.') }}</strong></span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="space-6"></div>
                                            <div class="well" style="display: flex;justify-content: space-between;">
                                                <div>
                                                    @foreach($bancos as $banco)
                                                        <span><strong>IBAN {{ $banco->moeda }}: </strong> {{ $banco->iban }}</span>
                                                        <br>
                                                    @endforeach
                                                </div>
                                                <div>
                                                    <a href="#" class="btn btn-primary btn-app radius-4" wire:click.prevent="emitirDocumento"
                                                       wire:keydown.enter="preventEnter"
                                                    >
                                                        <span wire:loading.remove wire:target="emitirDocumento">
                                                        Finalizar
                                                    </span>
                                                        <span wire:loading wire:target="emitirDocumento">
                                                        <span class="loading"></span>
                                                        Aguarde...
                                                    </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->


    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

</div>

<script>
    // Função para formatar a data no formato "dd/mm/aaaa hh:mm"
    function formatarData(data) {
        var dia = String(data.getDate()).padStart(2, '0');
        var mes = String(data.getMonth() + 1).padStart(2, '0');
        var ano = data.getFullYear();
        var hora = String(data.getHours()).padStart(2, '0');
        var minuto = String(data.getMinutes()).padStart(2, '0');
        var segundo = String(data.getSeconds()).padStart(2, '0');
        return dia + '/' + mes + '/' + ano + ' ' + hora + ':' + minuto + ':' + segundo;
    }

    // Função para atualizar o contador de tempo
    function atualizarContador() {
        // Obter a data e hora atual
        var agora = new Date();

        // Definir a data e hora inicial (pode ser uma data específica no passado)
        var horaInicial = new Date('2024-02-27T08:00:00');

        // Calcular a diferença em milissegundos entre agora e a hora inicial
        var diferenca = agora - horaInicial;

        // Calcular as horas, minutos e segundos a partir da diferença em milissegundos
        var horas = Math.floor(diferenca / (1000 * 60 * 60));
        var minutos = Math.floor((diferenca % (1000 * 60 * 60)) / (1000 * 60));
        var segundos = Math.floor((diferenca % (1000 * 60)) / 1000);

        // Atualizar o conteúdo do elemento HTML com o contador
        document.getElementById('contador').textContent = formatarData(agora);
    }

    // Chamar a função para atualizar o contador a cada segundo
    setInterval(atualizarContador, 1000);
</script>
