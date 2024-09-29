<div class="container mt-4">
    <h4>Cadastro de Assuntos</h4>

    <!-- Formulário de cadastro/atualização -->
    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição do Assunto</label>
            <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao" wire:model="descricao">
            @error('descricao') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">
            {{ $assuntoId ? 'Atualizar Assunto' : 'Cadastrar Assunto' }}
        </button>
    </form>

    <!-- Lista de assuntos cadastrados -->
    <h5 class="mt-5">Assuntos Cadastrados</h5>
    <ul class="list-group">
        @forelse ($assuntos as $assunto)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $assunto['descricao'] }}
                <div>
                    <button class="btn btn-sm btn-warning" wire:click="editarAssunto({{ $assunto['codas'] }}, '{{ $assunto['descricao'] }}')">Atualizar</button>
                    <button class="btn btn-sm btn-danger" wire:click="deletarAssunto({{ $assunto['codas'] }})">Deletar</button>
                </div>
            </li>
        @empty
            <li class="list-group-item">Nenhum assunto cadastrado.</li>
        @endforelse
    </ul>
</div>
