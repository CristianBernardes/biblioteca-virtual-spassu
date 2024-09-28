<?php

namespace App\Traits;

/**
 *
 */
trait IziToastTrait
{
    /**
     * @param string $title
     * @param string $message
     * @param string $color
     * @return void
     */
    public function enviarNotificacao(
        string $title = 'Sucesso',
        string $message = 'Dado salvo com sucesso',
        string $color = 'green'
    )
    {
        $this->dispatch('mostrarIziToast', title: $title, message: $message, color: $color);
    }
}
