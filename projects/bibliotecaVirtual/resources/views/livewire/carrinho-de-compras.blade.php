<div class="container mt-5">
    <!-- Valor total do carrinho -->
    <div class="row mb-4">
        <div class="col-md-12 text-end">
            <span class="fw-bold">Valor Total do Carrinho: {{ formatarMoeda($total) }}</span>
        </div>
    </div>

    <!-- Lista de livros no carrinho -->
    @forelse($livros as $livro)
        <div class="row mb-4">
            <div class="col-md-2">
                <img
                    src="{{ $livro->capa ? getPathToFile($livro->capa->caminho_imagem) : asset('assets/images/semcapa.png') }}"
                    alt="Capa do Livro"
                    class="img-fluid"
                    style="max-width: 100%; height: auto; object-fit: contain;"
                >
            </div>

            <div class="col-md-6">
                <h5>{{ $livro->titulo }}</h5>
                <p>Editora: {{ $livro->editora }}</p>
                <p>Ano de Publicação: {{ $livro->ano_publicacao }}</p>
                <p>Valor Unitário: {{ formatarMoeda($livro->valor) }}</p>
            </div>

            <div class="col-md-4 d-flex flex-column">
                <!-- Seletor de quantidade -->
                <label for="quantidade-{{ $livro->codl }}">Quantidade:</label>
                <input
                    type="number"
                    id="quantidade-{{ $livro->codl }}"
                    min="1"
                    wire:model="quantidades.{{ $livro->codl }}"
                    wire:change="atualizarQuantidade({{ $livro->codl }}, $event.target.value)"
                    class="form-control"
                    style="width: 100px;"
                >

                <p class="mt-3">Subtotal: {{ formatarMoeda($livro->valor * $quantidades[$livro->codl]) }}</p>

                <!-- Botão para remover o livro do carrinho -->
                <button wire:click="removerDoCarrinho({{ $livro->codl }})" class="btn btn-danger mt-2">
                    Remover
                </button>
            </div>
        </div>
    @empty
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted">Seu carrinho está vazio.</p>
            </div>
        </div>
    @endforelse

    <!-- Cupom de desconto e botão de comprar -->
    @if(count($livros) > 0)
        <div class="row mt-4">
            <div class="col-md-6">
                <input
                    type="text"
                    wire:model="cupom"
                    placeholder="Insira seu cupom de desconto"
                    class="form-control"
                />
                <button wire:click="aplicarCupom" class="btn btn-primary mt-2">
                    Aplicar Cupom
                </button>
            </div>

            <div class="col-md-6 text-end">
                <button onclick="executarCompra()" class="btn btn-success">
                    Comprar
                </button>
            </div>
        </div>
    @endif
</div>
<script>
    function executarCompra(){
        iziToast.show({
            title: 'Sucesso',
            message: 'Compra realizada com sucesso!',
            color: 'green',
            position: 'center'
        });

        setTimeout(function() {
            @this.comprar()
        }, 3000);
    }
</script>
