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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{url('assets/css/welcome.css')}}">
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
                        @livewire('livros')
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+"
        crossorigin="anonymous"></script>

@livewireScripts
</body>
</html>
