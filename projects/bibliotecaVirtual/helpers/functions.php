<?php

function formatarMoeda($valor, $locale = 'pt_BR', $currency = 'BRL'): false|string
{
    $formatter = new \NumberFormatter($locale,  \NumberFormatter::CURRENCY);

    return $formatter->formatCurrency($valor, $currency);
}
