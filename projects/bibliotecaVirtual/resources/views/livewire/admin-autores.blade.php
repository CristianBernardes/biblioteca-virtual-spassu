<div class="container mt-4">
    <h4>Cadastro de Autores</h4>

    <!-- Formulário de cadastro/atualização -->
    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Autor</label>
            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" wire:model="nome">
            @error('nome') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">
            {{ $autorId ? 'Atualizar Autor' : 'Cadastrar Autor' }}
        </button>
        @if($autorId)
            <button type="button" class="btn btn-secondary" wire:click="reset('nome', 'autorId')">Cancelar</button>
        @endif
    </form>

    <!-- Lista de autores cadastrados -->
    <h5 class="mt-5">Autores Cadastrados</h5>
    <ul class="list-group">
        @forelse ($autores as $autor)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $autor['nome'] }}
                <div>
                    <button class="btn btn-sm btn-warning"
                            wire:click="editarAutor({{ $autor['codau'] }}, '{{ $autor['nome'] }}')">Atualizar
                    </button>
                    <button class="btn btn-sm btn-danger" wire:click="deletarAutor({{ $autor['codau'] }})">Deletar
                    </button>
                </div>
            </li>
        @empty
            <li class="list-group-item">Nenhum autor cadastrado.</li>
        @endforelse
    </ul>
</div>
