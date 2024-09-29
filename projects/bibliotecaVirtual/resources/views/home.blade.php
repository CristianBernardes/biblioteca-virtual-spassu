@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (auth()->user()->is_admin)
                            <!-- Abas para o usuário admin -->
                            <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="livros-tab" data-bs-toggle="tab" href="#livros"
                                       role="tab" aria-controls="livros" aria-selected="true">Livros</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="autores-tab" data-bs-toggle="tab" href="#autores" role="tab"
                                       aria-controls="autores" aria-selected="false">Autores</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="assuntos-tab" data-bs-toggle="tab" href="#assuntos"
                                       role="tab" aria-controls="assuntos" aria-selected="false">Assuntos</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="relatorio-tab" data-bs-toggle="tab" href="#relatorio"
                                       role="tab" aria-controls="relatorio" aria-selected="false">Relatório de
                                        Vendas</a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="livros" role="tabpanel"
                                     aria-labelledby="livros-tab">
                                    @livewire('admin-livros')
                                </div>
                                <div class="tab-pane fade" id="autores" role="tabpanel" aria-labelledby="autores-tab">
                                    @livewire('admin-autores')
                                </div>
                                <div class="tab-pane fade" id="assuntos" role="tabpanel" aria-labelledby="assuntos-tab">
                                    @livewire('admin-assuntos')
                                </div>
                                <div class="tab-pane fade" id="relatorio" role="tabpanel"
                                     aria-labelledby="relatorio-tab">
                                    @livewire('admin-relatorio')
                                </div>
                            </div>
                        @else
                            <!-- Aba única para usuários comuns -->
                            <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="compras-tab" data-bs-toggle="tab" href="#compras"
                                       role="tab" aria-controls="compras" aria-selected="true">Minhas Compras</a>
                                </li>
                            </ul>

                            <!-- Tab Content para usuário comum -->
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="compras" role="tabpanel"
                                     aria-labelledby="compras-tab">
                                    @livewire('minhas-compras')
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
