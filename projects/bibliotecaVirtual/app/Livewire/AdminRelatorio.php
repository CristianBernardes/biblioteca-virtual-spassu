<?php

namespace App\Livewire;

use App\Services\ComprasViewService;
use Livewire\Component;

/**
 * Class AdminRelatorio
 *
 * Componente Livewire para exibir o relatório de vendas no painel de administração.
 */
class AdminRelatorio extends Component
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
     * AdminRelatorio constructor.
     *
     * Inicializa o serviço de compras.
     */
    public function __construct()
    {
        $this->comprasViewService = new ComprasViewService();
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
        $this->compras = $this->comprasViewService->getComprasView();
    }

    /**
     * Renderiza a view com os dados de compras.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin-relatorio', [
            'compras' => $this->compras
        ]);
    }
}
