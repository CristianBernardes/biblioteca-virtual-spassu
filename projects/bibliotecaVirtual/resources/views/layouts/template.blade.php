<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{url('assets/css/welcome.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/iziToast/iziToast.min.css')}}" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @livewireStyles
</head>
<body class="font-sans antialiased bg-light text-dark">
<div class="bg-light text-dark">
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100">
        <div class="container text-center">
            <header class="row align-items-center py-5">
                <div class="col-12 col-lg-4 text-center">
                    <img src="{{url('assets/images/spassu_logo.png')}}" class="img-fluid">
                </div>
                <div class="col-12 col-lg-8 text-end">
                    @if (Route::has('login'))
                        <nav class="d-inline-flex">
                            @auth
                                <a href="{{ url('/home') }}" class="btn btn-link text-dark">
                                    Home
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-link text-dark">
                                    Entrar
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-link text-dark">
                                        Registrar
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            <main class="mt-4">
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
            </main>

            <footer class="py-4 text-center">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous">
</script>
<script src="{{url('assets/js/iziToast/iziToast.min.js')}}" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
@livewireScripts
<script>
    function exibirIziToast(notificacao) {

        iziToast.show({
            title: notificacao.title,
            message: notificacao.message,
            color: notificacao.color,
            position: 'center'
        });
    }

    Livewire.on('mostrarIziToast', function (notificacao) {
        exibirIziToast(notificacao);
    });
</script>
</body>
</html>
