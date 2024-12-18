<!DOCTYPE html>
<html>
<head>
    <title>Redifir senha</title>
</head>
<body>
<h1>{{ $empresa }}</h1>
<p>Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.</p>
<p>Este link de redefinição de senha expirará em 60 minutos.</p>
<p>clica no link abaixo para redefinição da senha.</p>
<p><a href="{{$url}}" target="_blank"
      style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px;text-decoration:none;color:#3E8EB8">{{ $url }}</a>
</p>
<i>Obrigado por se juntar a nós!</i>
</body>
</html>
