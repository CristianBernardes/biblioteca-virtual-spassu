<div class="container mt-4">
    <h4>Cadastro de Livros</h4>

    <!-- Formulário de cadastro/atualização -->
    <form wire:submit.prevent="submit">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo"
                       wire:model="titulo">
                @error('titulo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="editora" class="form-label">Editora</label>
                <input type="text" class="form-control @error('editora') is-invalid @enderror" id="editora"
                       wire:model="editora">
                @error('editora') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="edicao" class="form-label">Edição</label>
                <input type="number" class="form-control @error('edicao') is-invalid @enderror" id="edicao"
                       wire:model="edicao">
                @error('edicao') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="ano_publicacao" class="form-label">Ano de Publicação</label>
                <input type="number" class="form-control @error('ano_publicacao') is-invalid @enderror"
                       id="ano_publicacao"
                       wire:model="ano_publicacao">
                @error('ano_publicacao') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="number" step="0.01" class="form-control @error('valor') is-invalid @enderror" id="valor"
                       wire:model="valor">
                @error('valor') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="capa" class="form-label">Capa</label>
                <input type="file" class="form-control @error('capa') is-invalid @enderror" id="capa" wire:model="capa">
                @error('capa') <span class="text-danger">{{ $message }}</span> @enderror

                <!-- Preview da capa -->
                @if ($capa)
                    <div class="mt-2">
                        <img src="{{ $capa->temporaryUrl() }}" alt="Preview da Capa" width="100" height="auto">
                    </div>
                @elseif ($livroId && isset($livro->capa))
                    <div class="mt-2">
                        <img src="{{ getPathToFile($livro->capa->caminho_imagem) }}" alt="Capa Atual" width="100" height="auto">
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="autores" class="form-label">Autores</label>
                <select multiple class="form-control @error('autores') is-invalid @enderror" id="autoresLivros"
                        wire:model="autores">
                    @foreach($autoresDisponiveis as $autor)
                        <option value="{{ $autor->codau }}">{{ $autor->nome }}</option>
                    @endforeach
                </select>
                @error('autores') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="assuntos" class="form-label">Assuntos</label>
                <select multiple class="form-control @error('assuntos') is-invalid @enderror" id="assuntosLirvos"
                        wire:model="assuntos">
                    @foreach($assuntosDisponiveis as $assunto)
                        <option value="{{ $assunto->codas }}">{{ $assunto->descricao }}</option>
                    @endforeach
                </select>
                @error('assuntos') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ $livroId ? 'Atualizar Livro' : 'Salvar Livro' }}</button>
    </form>

    <!-- Lista de livros cadastrados -->
    <h5 class="mt-5">Livros Cadastrados</h5>
    <ul class="list-group">
        @forelse ($livros as $livro)
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <img
                            src="{{ $livro->capa ? getPathToFile($livro->capa->caminho_imagem) : asset('assets/images/semcapa.png') }}"
                            alt="Capa" width="50">
                        <strong>{{ $livro->titulo }}</strong> ({{ $livro->editora }} - {{ $livro->ano_publicacao }})
                    </span>
                    <div>
                        <button class="btn btn-warning btn-sm" wire:click="editarLivro({{ $livro->codl }})">Editar
                        </button>
                        <button class="btn btn-danger btn-sm" wire:click="deletarLivro({{ $livro->codl }})">Deletar
                        </button>
                    </div>
                </div>
            </li>
        @empty
            <li class="list-group-item">Nenhum livro cadastrado.</li>
        @endforelse
    </ul>
</div>
