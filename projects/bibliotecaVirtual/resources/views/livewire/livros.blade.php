<div class="container mt-5">
    <!-- Linha de pesquisa e link para o carrinho -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <input
                type="text"
                wire:model.live="termoPesquisa"
                placeholder="Pesquisar livros..."
                class="form-control"
            />
        </div>

        <!-- Exibir o link para o carrinho se houver itens no carrinho -->
        @if($itensCarrinho > 0)
            <div class="col-md-2 text-end">
                <a href="{{route('carrinho')}}" class="btn btn-success">
                    Ir para o Carrinho ({{ $itensCarrinho }})
                </a>
            </div>
        @endif
    </div>

    <!-- Lista de livros -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @forelse($livros as $livro)
            <div class="col mb-4">
                <div class="card h-100 shadow-sm">
                    <img
                        src="{{ $livro->capa ? asset($livro->caminho_imagem) : asset('assets/images/semcapa.png') }}"
                        alt="Capa do Livro"
                        class="card-img-top"
                        style="max-width: 100%; height: auto; object-fit: contain;"
                    >

                    <div class="card-body">
                        <h5 class="card-title">{{ $livro->titulo }}</h5>
                        <p class="card-text">Editora: {{ $livro->editora }}</p>
                        <p class="card-text">Ano de Publicação: {{ $livro->ano_publicacao }}</p>
                        <p class="card-text">Valor: {{ formatarMoeda($livro->valor) }}</p>

                        <!-- Botão de Adicionar ao Carrinho -->
                        @if($mostrarBotaoAddCarrinho)
                            <button wire:click="adicionarAoCarrinho({{ $livro->codl }})" class="btn btn-primary mt-3">
                                Adicionar ao Carrinho
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">Nenhum livro encontrado.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $livros->links() }}
    </div>
</div>
