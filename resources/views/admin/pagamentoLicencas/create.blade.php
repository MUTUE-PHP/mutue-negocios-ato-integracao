@section('title','Novo Pagamento')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            NOVO PAGAMENTO
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-warning hidden-sm hidden-xs">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                Os campos marcados com
                <span class="tooltip-target" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-question-circle bold text-danger"></i></span>
                são de preenchimento obrigatório.
            </div>
        </div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success alert-success col-xs-12" style="left: 0%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fa fa-check-square-o bigger-150"></i>{{ Session::get('success') }}</h5>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <form class="filter-form form-horizontal validation-form" id="validation-form">
                @csrf
                <div class="second-row">
                    <div class="tabbable">
                        <div class="tab-content profile-edit-tab-content">
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-7">
                                    <label class="control-label bold label-select2" for="empresaId">Cliente<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="pagamento.empresaId" data="empresaId"
                                            style="height:35px;<?= $errors->has('pagamento.empresaId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">Informe a empresa</option>
                                        @foreach($empresas as $empresa)
                                            <option value="{{ $empresa->id }}">{{ Str::upper($empresa->nome) }} /
                                                NIF:{{ $empresa->nif }} /
                                                TELEFONE: {{$empresa->pessoal_Contacto}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('pagamento.empresaId'))
                                        <span class="help-block selectError"
                                              style="color: red;position: absolute;font-size: 12px;">
                                        <strong>{{ $errors->first('pagamento.empresaId') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-1">
                                    <label class="control-label bold label-select2" for="quantidade">QTD.<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="pagamento.quantidade"
                                            data="quantidade"
                                            style="height:35px;<?= $errors->has('pagamento.licencaId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                    </select>
                                    @if ($errors->has('pagamento.licencaId'))
                                        <span class="help-block selectError"
                                              style="color: red;position: absolute;font-size: 12px;">
                                        <strong>{{ $errors->first('pagamento.licencaId') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="licencaId">Licença<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="pagamento.licencaId" data="licencaId"
                                            style="height:35px;<?= $errors->has('pagamento.licencaId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">Informe a licença</option>
                                        @foreach($licencas as $licenca)
                                            @if($licenca->id !== 1)
                                                <option value="{{ $licenca->id }}">{{ Str::upper($licenca->designacao) }}
                                                    - {{ number_format($licenca->valor, 2, ',','.') }}Kz
                                                </option>
                                            @endif

                                        @endforeach
                                    </select>
                                    @if ($errors->has('pagamento.licencaId'))
                                        <span class="help-block selectError"
                                              style="color: red;position: absolute;font-size: 12px;">
                                        <strong>{{ $errors->first('pagamento.licencaId') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="numeroOperacaoBancaria">Nº de
                                        operação bancária<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" wire:model="pagamento.numeroOperacaoBancaria"
                                               class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('pagamento.numeroOperacaoBancaria') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('pagamento.numeroOperacaoBancaria'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('pagamento.numeroOperacaoBancaria') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="dataPagamentoBanco">Data
                                        pagamento no banco<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="datetime-local" wire:model="pagamento.dataPagamentoBanco"
                                               class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('pagamento.dataPagamentoBanco') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                        </span>
                                    </div>
                                    @if ($errors->has('pagamento.dataPagamentoBanco'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('pagamento.dataPagamentoBanco') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="formaPagamentoId">Forma
                                        pagamento no banco<b class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="pagamento.formaPagamentoId"
                                            data="formaPagamentoId"
                                            style="height:35px;<?= $errors->has('pagamento.formaPagamentoId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">Informe a forma de pagamento</option>
                                        @foreach($formasPagamento as $formaPagamento)
                                            <option
                                                value="{{ $formaPagamento->id }}">{{ Str::upper($formaPagamento->descricao) }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @if ($errors->has('pagamento.formaPagamentoId'))
                                        <span class="help-block selectError"
                                              style="    color: red;position: absolute;font-size: 12px;">
                                        <strong>{{ $errors->first('pagamento.formaPagamentoId') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="contaMovimentadaId">Informe a
                                        conta movimentada<b class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="pagamento.contaMovimentadaId"
                                            data="contaMovimentadaId"
                                            style="height:35px;<?= $errors->has('pagamento.contaMovimentadaId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">Informe a conta movimentada</option>
                                        @foreach($bancos as $banco)
                                            <option value="{{ $banco->id }}">{{ Str::upper($banco->sigla) }}/ Nº
                                                CONTA: {{ $banco->coordernadaBancaria->num_conta }}
                                                / IBAN: {{$banco->coordernadaBancaria->iban}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('pagamento.contaMovimentadaId'))
                                        <span class="help-block selectError"
                                              style="color: red;position: absolute;font-size: 12px;">
                                        <strong>{{ $errors->first('pagamento.contaMovimentadaId') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="name">Comprovativo
                                        bancário(png,jpg,jpeg)<b class="red fa fa-question-circle">

                                        </b></label>
                                    <div class="input-group">
                                        <input type="file" wire:model="pagamento.comprovativoBancario"
                                               class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('pagamento.comprovativoBancario') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                        </span>
                                    </div>
                                    @if ($errors->has('pagamento.comprovativoBancario'))
                                        <span class="help-block" style="color: red; font-weight: bold;">
                                        <strong>{{ $errors->first('pagamento.comprovativoBancario') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    <label class="control-label bold label-select2" for="name">Observação</label>
                                    <div class="input-group">
                                        <textarea class="form-control" wire:model="pagamento.observacao"
                                                  cols="200"></textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="search-btn" type="submit" style="border-radius: 10px"
                                            wire:click.prevent="enviarPagamento">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        <span wire:loading.remove wire:target="enviarPagamento">
                                            Enviar Pagamento
                                        </span>
                                        <span wire:loading wire:target="enviarPagamento">
                                            <span class="loading"></span>

                                            Aguarde...</span>
                                    </button>

                                    &nbsp; &nbsp;
                                    <a class="btn btn-danger" type="reset" href="{{ route('pagamentoLicencasIndex') }}"
                                       style="border-radius: 10px">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Voltar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
