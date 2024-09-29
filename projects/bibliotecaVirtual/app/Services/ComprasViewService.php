<?php

namespace App\Services;

use App\Models\ComprasView;

/**
 *
 */
class ComprasViewService
{
    /**
     * @var ComprasView
     */
    private ComprasView $model;

    /**
     *
     */
    public function __construct()
    {
        $this->model = new ComprasView();
    }

    /**
     * @param int|null $idUsuario
     * @return mixed
     */
    public function getComprasView(?int $idUsuario = null)
    {
        return $this->model->when($idUsuario, function ($query) use ($idUsuario) {
            $query->where('id_usuario', $idUsuario);
        })->get();
    }
}
