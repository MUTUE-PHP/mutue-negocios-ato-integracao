{{--@component('frontOffice/header', ['title'=>'Redefinir a Senha'])--}}
{{--@endcomponent--}}

{{--<div class="hero row align-items-center">--}}

{{--    <div class="col-md-6">--}}
{{--        <!-- <h2>Best & Trusted</h2> -->--}}
{{--        <h2><span>Mutue</span> Negócios</h2>--}}
{{--        <h2>A solução da sua empresa</h2><br>--}}
{{--    </div>--}}

{{--    <div class="col-md-6">--}}
{{--        <div class="form">--}}
{{--            <h3>Nova senha</h3>--}}
{{--            @if (session('status'))--}}
{{--            <div class="alert alert-success" role="alert">--}}
{{--                {{ session('status') }}--}}
{{--            </div>--}}
{{--            @endif--}}
{{--            <form method="POST" action="{{ route('password.update', $token) }}" class="php-email-form">--}}
{{--                @csrf--}}
{{--                <input type="hidden" name="token" value="{{ $token }}">--}}
{{--                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" autofocus/>--}}
{{--                @error('email')--}}
{{--                <span class="invalid-feedback" role="alert">--}}
{{--                    <strong>{{ $message }}</strong>--}}
{{--                </span>--}}
{{--                @enderror--}}
{{--                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Nova senha">--}}
{{--                @error('password')--}}
{{--                <span class="invalid-feedback" role="alert">--}}
{{--                    <strong>{{ $message }}</strong>--}}
{{--                </span>--}}
{{--                @enderror--}}
{{--                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar nova senha">--}}

{{--                <button class="btn btn-block" type="submit">{{ __('redefinir a senha') }}</button>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<style>--}}
{{--.is-invalid{--}}
{{--    border: 1px solid red !important;--}}
{{--}--}}
{{--</style>--}}
{{--@component('frontOffice/footer')--}}
{{--@endcomponent--}}

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
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="{{asset('assets/login/images/img-02.jpg')}}" alt="IMG">
            </div>

            <form method="POST" action="{{ route('password.update', $token) }}" class="login100-form validate-form"
                  id="form-submit">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
          
                <span class="login100-form-title mb-4">
					</span>
                <label>Informe a nova senha</label>
                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" style="<?= $errors->has('email') ? 'border-color: red;border:1px solid red' : '' ?>" type="email" name="email"
                           value="{{ $email ?? old('email') }}" disabled required>
                    <input type="email" name="email" hidden="hidden" value="{{ $email ?? old('email') }}"/>
                    <span class="focus-input100"></span>

                    <span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input" style="margin-bottom: 20px">
                    <input class="input100" style="<?= $errors->has('password') ? 'border-color: red;border:1px solid red' : '' ?>" type="password" name="password"
                           placeholder="Nova senha" required>
                    @if ($errors->has('password'))
                        <span class="help-block" style="    color: red;position: absolute;font-size: 12px;left: 16px;">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                    <span class="focus-input100"></span>

                    <span class="symbol-input100">
							<i class="fa fa-key" aria-hidden="true"></i>
						</span>
                </div>
                <div class="wrap-input100 validate-input" style="margin-bottom: 20px">
                    <input class="input100" style="<?= $errors->has('password') ? 'border-color: red;border:1px solid red' : '' ?>" type="password" name="password_confirmation"
                           placeholder="Confirmar nova senha" required>
                    @if ($errors->has('password'))
                        <span class="help-block" style="    color: red;position: absolute;font-size: 12px;left: 16px;">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                    <span class="focus-input100"></span>

                    <span class="symbol-input100">
							<i class="fa fa-key" aria-hidden="true"></i>
						</span>
                </div>
                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" type="submit">
                        redefinir a senha
                    </button>
                </div>

                <div class="text-center p-t-12">
						<span class="txt1">
							Lembra
						</span>
                    <a class="txt2" href="{{ route('password.reset') }}">
                        da sua senha?
                    </a>
                </div>


                <div class="text-center p-t-136">
                    <a class="txt2" href="#">
                        Contacta o Suporte Técnico
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
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
