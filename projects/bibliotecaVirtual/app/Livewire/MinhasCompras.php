<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\ComprasViewService;
use Livewire\Component;

class MinhasCompras extends Component
{
    /**
     * @var array $compras Armazena as informações de compras retornadas pelo serviço.
     */
    public $compras;

    /**
     * @var ComprasViewService $comprasViewService Serviço para obter os dados de vendas.
     */
    protected $comprasViewService;

    /**
     * @var null|User Usuário autenticado
     */
    private $user;

    /**
     * AdminRelatorio constructor.
     *
     * Inicializa o serviço de compras.
     */
    public function __construct()
    {
        $this->comprasViewService = new ComprasViewService();
        $this->user = auth()->user();
    }

    /**
     * Método mount para carregar os dados ao inicializar o componente.
     *
     * Carrega todas as compras para exibição.
     *
     * @return void
     */
    public function mount()
    {
        $this->compras = $this->comprasViewService->getComprasView($this->user->id);
    }

    public function render()
    {
        return view('livewire.minhas-compras', [
            'compras' => $this->compras
        ]);
    }
}
