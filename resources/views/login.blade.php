<!DOCTYPE html>
<html lang="en">
<head>
    <title>mutue negócios</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    {{-- <link rel="icon" type="image/png" href="{{asset('assets/login/images/icons/favicon.ico')}}"/> --}}
    <link rel="shortcut icon" sizes="57x57" href="{{asset('favicon/apple-icon-57x57.png')}}">
    <link rel="shortcut icon" sizes="60x60" href="{{asset('favicon/apple-icon-60x60.png')}}">
    <link rel="shortcut icon" sizes="72x72" href="{{asset('favicon/apple-icon-72x72.png')}}">
    <link rel="shortcut icon" sizes="76x76" href="{{asset('favicon/apple-icon-76x76.png')}}">
    <link rel="shortcut icon" sizes="114x114" href="{{asset('favicon/apple-icon-114x114.png')}}">
    <link rel="shortcut icon" sizes="120x120" href="{{asset('favicon/apple-icon-120x120.png')}}">
    <link rel="shortcut icon" sizes="144x144" href="{{asset('favicon/apple-icon-144x144.png')}}">
    <link rel="shortcut icon" sizes="152x152" href="{{asset('favicon/apple-icon-152x152.png')}}">
    <link rel="shortcut icon" sizes="180x180" href="{{asset('favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon/favicon-16x16.png')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/animate/animate.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/css-hamburgers/hamburgers.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/vendor/select2/select2.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login/css/myStyle.css')}}">
    <!--===============================================================================================-->
</head>
<body>
<div class="limiter">
    {{-- <source src="{{asset('assets/login/images/bgv.mp4')}}" type="video/mp4"> --}}
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="{{asset('assets/login/images/TK3.png')}}" alt="IMG">
                <p class="text-bold  text-center login100-form-title">Sistema Integrado de
                    Gestão</p>
            </div>
            <form action="{{ route('login') }}" id="contact-form" method="POST" class="login100-form">
                @csrf
                <span class="login100-form-title mb-4">
						Faça Login
					</span>
                <div class="mb-2">
                    @if ($errors->has('email'))
                        <span class="block input-icon input-icon-right">
                        <strong style="color: #c73030;font-size: 12px;">{{ $errors->first('email') }}</strong>
                    </span>
                    @endif

                    @if ($errors->has('password'))
                        <span class="help-block" style="color: #c73030;font-size: 12px;">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="email" placeholder="Email/Telefone" value="{{ old('email') }}" required>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-envelope"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" value="{{ old('password') }}" required type="password" name="password"
                           id="password" placeholder="Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                </div>
                <div class="control-group d-none" aria-hidden="true">
                    <select class="custom-select" name="tipoUser">
                        <option value="2" selected>Empresa</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" type="submit">
                        Login
                    </button>
                </div>

                <div class="text-center p-t-12">
						<span class="txt1">
							Esqueceu
						</span>
                    <a class="txt2" href="{{ route('password.reset') }}">
                        a sua senha?
                    </a>
                </div>
                <div class="text-center p-t-136">
                    <a class="txt2" href="https://www.ramossoft.com/contatos">
                        Contacta o Suporte Técnico
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
                    <div style="display: flex;flex-direction: column; text-align: left">
                        <a class="txt2" target="_blank" href="https://logistica.mutue.ao/login">> Compras e logísticas</a>
                        <a class="txt2" target="_blank" href="https://gp.mutue.ao/">> Gestão de pessoas - RH</a>
                        <a class="txt2" target="_blank" href="https://contascertas.mutue.ao/">> Contas certas</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--===============================================================================================-->
<script src="{{asset('assets/login/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('assets/login/vendor/bootstrap/js/popper.js')}}"></script>
<script src="{{asset('assets/login/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('assets/login/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('assets/login/vendor/tilt/tilt.jquery.min.js')}}"></script>
<script>
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.querySelector(".toggle-password i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        }
    }
</script>
<!--===============================================================================================-->
<script src="{{asset('assets/login/js/main.js')}}"></script>

</body>
</html>
