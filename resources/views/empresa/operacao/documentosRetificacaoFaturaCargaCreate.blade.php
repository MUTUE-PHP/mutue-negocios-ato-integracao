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
                                        <h5>NOTA DE CRÉDITO - RETIFICAÇÃO</h5>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-24">
                                            <div class="row">
                                                <div class="form-group has-info bold"
                                                     style="left: 0%; position: relative;">
                                                    <div class="col-md-12" style="margin-bottom: 10px">
                                                        <label class="control-label bold label-select2"
                                                               for="numeracaoFactura">Buscar
                                                            factura<b class="red fa fa-question-circle"></b></label>
                                                        <input type="text" value="<?= $numeracaoFatura?>" disabled
                                                               class="form-control"
                                                               style="height: 35px; font-size: 10pt;<?= $errors->has('recibo.numeracaoFactura') ? 'border-color: #ff9292;' : '' ?>"/>
                                                        @if ($errors->has('recibo.numeracaoFactura'))
                                                            <span class="help-block"
                                                                  style="color: red; font-weight: bold">
                                                                <strong>{{ $errors->first('numeracaoFatura') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-info bold"
                                                     style="left: 0%; position: relative">
                                                    <div class="col-md-4" style="margin-bottom: 10px">
                                                        <label class="control-label bold label-select2"
                                                               for="nomeCliente">Nome do cliente<b
                                                                class="red fa fa-question-"></b></label>
                                                        <div class="input-group">
                                                            <input type="text" value="<?= $fatura['nomeCliente'] ?>"
                                                                   disabled
                                                                   class="form-control"
                                                                   style="height: 35px; font-size: 10pt"/>
                                                            <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="margin-bottom: 10px">
                                                        <label class="control-label bold label-select2"
                                                               for="nifCliente">NIF</label>
                                                        <div class="input-group">
                                                            <input type="text" value="<?= $fatura['nifCliente'] ?>"
                                                                   disabled
                                                                   class="form-control"
                                                                   style="height: 35px; font-size: 10pt"/>
                                                            <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="margin-bottom: 10px">
                                                        <label class="control-label bold label-select2"
                                                               for="nifCliente">Proprietário/Companhia
                                                            Aérea</label>
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   value="<?= $fatura['nomeProprietario'] ?>" disabled
                                                                   class="form-control"
                                                                   style="height: 35px; font-size: 10pt"/>
                                                            <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-info bold">
                                                        <div class="col-md-12" style="margin-bottom: 10px">
                                                            <label class="control-label bold label-select2"
                                                                   for="observacao">Observação<b
                                                                    class="red fa fa-question-circle"></b></label>
                                                            <div class="input-group">
                                                                <input type="text"
                                                                       wire:model="observacao"
                                                                       class="form-control"
                                                                       style="height: 35px; font-size: 10pt;<?= $errors->has('observacao') ? 'border-color: #ff9292;' : '' ?>"/>
                                                                @if ($errors->has('observacao'))
                                                                    <span class="help-block"
                                                                          style="color: red; font-weight: bold">
                                                                        <strong>{{ $errors->first('observacao') }}</strong>
                                                                    </span>
                                                                @endif
                                                                <span class="input-group-addon" id="basic-addon1">
                                                                        <i class="ace-icon fa fa-info bigger-150 text-info"
                                                                            data-target="form_supply_price_smartprice"></i>
                                                                        </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="widget-box">
                                                        <div class="widget-header">
                                                        </div>
                                                        <div class="widget-body">
                                                            <div class="widget-main" style="margin-bottom: 10px">
                                                                <form class="form-inline">
                                                                    <div class="form-group"
                                                                         style="margin-right: 15px">
                                                                        <label>Carta de Porte(AWB)</label>
                                                                        <div>
                                                                            <input type="text"
                                                                                   wire:model="fatura.cartaDePorte"
                                                                                   style="width: 150px;<?= $errors->has('fatura.cartaDePorte') ? 'border-color: #ff9292;' : '' ?>"
                                                                                   class="input-small"
                                                                                   placeholder="AWB"/>
                                                                            @if ($errors->has('fatura.cartaDePorte'))
                                                                                <span class="help-block"
                                                                                      style="color: red; font-weight: bold">
                                                                                    <strong>{{ $errors->first('fatura.cartaDePorte') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group"
                                                                         style="margin-right: 15px">
                                                                        <label>Peso(Kg)</label>
                                                                        <div>
                                                                            <input type="text" id="money"
                                                                                   wire:model="fatura.peso"
                                                                                   value="0,00"
                                                                                   name="money"
                                                                                   class="input-small"
                                                                                   style="width: 150px; <?= $errors->has('fatura.peso') ? 'border-color: #ff9292;' : '' ?>"
                                                                                   placeholder="Peso"/>
                                                                            @if ($errors->has('fatura.peso'))
                                                                                <span class="help-block"
                                                                                      style="color: red; font-weight: bold">
                                                                                    <strong>{{ $errors->first('fatura.peso') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group"
                                                                         style="margin-right: 15px">
                                                                        <label>Data de Entrada</label>
                                                                        <div>
                                                                            <input type="datetime-local"
                                                                                   wire:model="fatura.dataEntrada"
                                                                                   class="input-small"
                                                                                   style="width: 150px; <?= $errors->has('fatura.dataEntrada') ? 'border-color: #ff9292;' : '' ?>"/>
                                                                            @if ($errors->has('fatura.dataEntrada'))
                                                                                <span class="help-block"
                                                                                      style="color: red; font-weight: bold">
                                                                                    <strong>{{ $errors->first('fatura.dataEntrada') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group"
                                                                         style="margin-right: 15px">
                                                                        <label>Data de Saída</label>
                                                                        <div>
                                                                            <input type="datetime-local"
                                                                                   wire:model="fatura.dataSaida"
                                                                                   class="input-small"
                                                                                   style="width: 150px; <?= $errors->has('fatura.dataSaida') ? 'border-color: #ff9292;' : '' ?>"/>
                                                                            @if ($errors->has('fatura.dataSaida'))
                                                                                <span class="help-block"
                                                                                      style="color: red; font-weight: bold">
                                                                                    <strong>{{ $errors->first('fatura.dataSaida') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-right: 15px">
                                                                        <label>Nº Dias</label>
                                                                        <div>
                                                                            <input type="text"
                                                                                   wire:model="fatura.nDias"
                                                                                   disabled class="input-small"
                                                                                   style="width: 50px"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-right: 15px">
                                                                        <label>Tipo Documento</label>
                                                                        <div>
                                                                            <select style="width: 100%;height: 34px"
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
                                                                    <div class="form-group" style="margin-right: 15px">
                                                                        <label>Tipo de Operação</label>
                                                                        <div>
                                                                            <select style="width: 100%;height: 34px"
                                                                                    wire:model="fatura.tipoOperacao"
                                                                                    name="ship"
                                                                                    rowid="6"
                                                                                    size="1"
                                                                                    class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                                <option value="1">Importação
                                                                                </option>
                                                                                <option value="2">Exportação</option>
                                                                                <option value="3">Transito</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-right: 15px">
                                                                        <label>Forma Pagamento</label>
                                                                        <div>
                                                                            <select style="width: 100%;height: 34px"
                                                                                    wire:model="fatura.formaPagamentoId"
                                                                                    name="ship"
                                                                                    rowid="6"
                                                                                    size="1"
                                                                                    class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                                @foreach($formasPagamentos as $formaPagamento)
                                                                                    <option
                                                                                        value="{{ $formaPagamento->id }}" <?= $formaPagamento['id'] == $fatura['formaPagamentoId'] ? 'selected' : '' ?>>{{ $formaPagamento->descricao }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Moeda</label>
                                                                        <div>
                                                                            <select style="width: 100%;height: 34px"
                                                                                    wire:model="fatura.moedaPagamento"
                                                                                    name="ship"
                                                                                    rowid="6"
                                                                                    size="1"
                                                                                    class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                                @foreach($moedas as $moeda)
                                                                                    <option
                                                                                        value="{{ $moeda['designacao'] }}" <?= $moeda['designacao'] == $fatura['moedaPagamento'] ? 'selected' : '' ?>>{{ $moeda['designacao'] }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-left: 15px">
                                                                        <label for="isencaoIva">Isenção IVA</label>
                                                                        <div>
                                                                            <input name="form-field-checkbox"
                                                                                   wire:model="fatura.isencaoIVA"
                                                                                   id="isencaoIva" type="checkbox"
                                                                                   class="ace input-lg"/>
                                                                            <span class="lbl bigger-140"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-left: 15px">
                                                                        <label for="retencao">Incluir Retenção</label>
                                                                        <div>
                                                                            <input name="form-field-checkbox"
                                                                                   wire:model="fatura.retencao"
                                                                                   id="retencao" type="checkbox"
                                                                                   class="ace input-lg"/>
                                                                            <span class="lbl bigger-140"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" style="margin-left: 15px">
                                                                        <label for="isencaoCargaTransito">Isenção
                                                                            24h/Carga trânsito</label>
                                                                        <div>
                                                                            <input name="form-field-checkbox"
                                                                                   wire:model="fatura.isencaoCargaTransito"
                                                                                   id="isencaoCargaTransito"
                                                                                   type="checkbox"
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
                                                <div class="col-md-6">
                                                    <div>

                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th style="width: 400px; text-align: left">
                                                                    Produto
                                                                </th>
                                                                <th style="text-align: center">Taxa</th>
                                                                <th style="text-align: center">Descontos</th>
                                                                <th style="text-align: center">Imposto</th>
                                                                <th style="text-align: right">Total</th>
                                                                <th style="text-align: center"></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($fatura['items'] as $key=> $faturaItem)
                                                                <tr>
                                                                    <td>{{++$key}}</td>
                                                                    <td style="width: 400px; text-align: left">{{ $faturaItem['nomeProduto'] }}</td>
                                                                    <td style="text-align: center">{{ $faturaItem['taxa'] }}</td>
                                                                    <td style="text-align: center">{{ $faturaItem['desconto'] }}
                                                                        %
                                                                    </td>
                                                                    <td style="text-align: center">{{ $faturaItem['valorImposto'] }}</td>
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
                                                <div class="col-md-6">
                                                    <div>
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>Produto</th>
                                                                <th>Desc(%)</th>
                                                                <th style="width: 130px">Tipo/Mercadorias</th>
                                                                <th>Sujeito a despacho aduaneiro</th>
                                                                <th>Especificação da mercadoria</th>
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
                                                                    <input type="number" wire:model="item.desconto"
                                                                           style="width: 70px; height: 25px">
                                                                </td>
                                                                <td class="center">
                                                                    <select style="width: 100%"
                                                                            wire:model="item.tipoMercadoriaId"
                                                                            name="ship" rowid="6"
                                                                            size="1"
                                                                            class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                        @foreach($tipoMercadorias as $mercadoria)
                                                                            <option
                                                                                value="{{$mercadoria->id}}">{{ $mercadoria->designacao }}</option>
                                                                        @endforeach

                                                                    </select>
                                                                </td>
                                                                <td class="center">
                                                                    <select style="width: 100%"
                                                                            wire:model="item.sujeitoDespachoId"
                                                                            name="ship" rowid="6"
                                                                            size="1"
                                                                            class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                        <option role="option" value="1">Sim</option>
                                                                        <option role="option" value="2">Não</option>

                                                                    </select>
                                                                </td>
                                                                <td class="center">
                                                                    <select style="width: 100%"
                                                                            wire:model="item.especificacaoMercadoriaId"
                                                                            name="ship" rowid="8"
                                                                            size="1"
                                                                            class="editable inline-edit-cell ui-widget-content ui-corner-all">
                                                                        @foreach($especificaoMercadorias as $especificacao)
                                                                            <option
                                                                                value="{{$especificacao->id}}">{{  substr($especificacao->designacao, 0, 40) }}</option>
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
                                                        IVA(%) :
                                                        <span>{{ number_format($fatura['taxaIva'], 2,',','.') }}%</span>
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
                                                        TAXA DE CÂMBIO(AOA/{{$fatura['moeda']}}) :
                                                        <span>{{ number_format($fatura['cambioDia'], 2,',','.') }}</span>
                                                    </h8>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5 pull-right">
                                                    <h8 class="pull-right">
                                                        CONTRAVALOR({{$fatura['moeda']}}) :
                                                        <span><strong>${{ number_format($fatura['contraValor'], 2,',','.') }}</strong></span>
                                                    </h8>
                                                </div>
                                            </div>

                                            <div class="space-6"></div>
                                            <div class="well" style="display: flex;justify-content: space-between;">
                                                <div>

                                                </div>
                                                <div>
                                                    <a href="#" class="btn btn-primary btn-app radius-4"
                                                       wire:click.prevent="emitirDocumento"
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