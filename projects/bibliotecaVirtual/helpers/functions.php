<?php

use Carbon\Carbon;

/**
 * Formata um valor monetário de acordo com a localidade e moeda especificada.
 *
 * @param float $valor
 * @param string $locale
 * @param string $currency
 * @return false|string
 */
function formatarMoeda($valor, $locale = 'pt_BR', $currency = 'BRL'): false|string
{
    $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);

    return $formatter->formatCurrency($valor, $currency);
}

/**
 * Formata uma data no formato "YYYY-MM-DD HH:MM:SS" para "DD/MM/YYYY às HH:MM:SS".
 *
 * @param string $data
 * @return string
 */
function formatarData($data): string
{
    return Carbon::parse($data)->format('d/m/Y \à\s H:i:s');
}

/**
 * @param $fileName
 * @return string
 */
function getPathToFile($fileName)
{
    return $fileName ? asset('storage/capas/' . $fileName) : null;
}

