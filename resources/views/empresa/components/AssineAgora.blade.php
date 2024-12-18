@if($diasRestantes < 31)
<div class="alert alert-warning hidden-sm hidden-xs assinatura" style="position: relative">
    <button type="button" class="close" data-dismiss="alert">
        <i class="ace-icon fa fa-times"></i>
    </button>
    <div class="alert alert-<?php echo $licencaGratis?"warning":"success"?> text-center" style="margin:0; padding:0; font-weight: bold">
        <span class="text-incompleteeee">A sua versão expira daqui a {{$diasRestantes}} dias</span>
    </div>
</div>

@endif

