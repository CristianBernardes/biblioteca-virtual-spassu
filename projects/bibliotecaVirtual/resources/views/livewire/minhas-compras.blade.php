<div class="container mt-4">
    @foreach ($compras as $compra)
        <div class="card mb-3">
            <div class="card-header">
                <strong>Código da Compra:</strong> {{ $compra['codigo_compra'] }} <br>
                <strong>Data da Compra:</strong> {{ formatarData($compra['data_compra']) }} <br>
                <strong>Valor Total dos Livros:</strong> {{ formatarMoeda($compra['valor_total_livros']) }} <br>
                <strong>Desconto:</strong> {{ $compra['desconto'] }}% <br>
                <strong>Valor Total Pago:</strong> {{ formatarMoeda($compra['valor_total_pago']) }} <br>
            </div>
            <div class="card-body">
                <h5>Livros Comprados</h5>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Nome do Livro</th>
                        <th>Quantidade</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($compra['livros_comprados'] as $livro)
                        <tr>
                            <td>{{ $livro['nome_livro'] }}</td>
                            <td>{{ $livro['quantidade'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
