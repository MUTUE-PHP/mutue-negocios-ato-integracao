@php use Illuminate\Support\Str; @endphp
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=p, initial-scale=1.0">
    <title>Confirmação de pagamento</title>
</head>
<body>
<p>
    Prezado Sr.(ª) {{ Str::title($nomeEmpresa) }}. Confirmamos a recepção do seu comprovativo de pagamento referente a compra n.º {{$codigo}} no nosso site de vendas on-line. Queira por favor aguardar a validação num prazo máximo de 24h.
</p>
<p>
    Desde já, agradecemos pela preferência nos nossos serviços.
</p>
<p>
    Mutue - Soluções Tecnológicas Inteligentes
</p>
<p>
    loja@mutue.net<br>
    +244 934660003
</p>
<p>
    Atenciosamente
</p>
</body>

</html>
