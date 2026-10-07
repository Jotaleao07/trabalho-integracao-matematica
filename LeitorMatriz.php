<?php

namespace App\Helpers;

use InvalidArgumentException;

/**
 * Converte o texto digitado no formulário em matriz.
 * Cada linha é uma linha da matriz; números separados por espaço.
 * Ex.: "1 2\n3 4"  ->  [[1, 2], [3, 4]]
 */
class LeitorMatriz
{
    public static function ler(string $texto): array
    {
        $matriz = [];
        foreach (preg_split('/\R/', trim($texto)) as $linha) {
            $linha = trim(str_replace(',', '.', $linha));
            if ($linha === '') {
                continue;
            }
            $numeros = [];
            foreach (preg_split('/\s+|;/', $linha) as $valor) {
                if ($valor === '') {
                    continue;
                }
                if (!is_numeric($valor)) {
                    throw new InvalidArgumentException("Valor inválido: \"$valor\".");
                }
                $numeros[] = $valor + 0;
            }
            $matriz[] = $numeros;
        }
        return $matriz;
    }

    /** Lê um vetor (uma linha ou uma coluna de números). */
    public static function lerVetor(string $texto): array
    {
        $vetor = [];
        foreach (self::ler($texto) as $linha) {
            foreach ($linha as $v) {
                $vetor[] = $v;
            }
        }
        return $vetor;
    }

    /** Formata um número para exibir na tela (até 4 casas, vírgula decimal). */
    public static function numero(float|int $v): string
    {
        $v = round($v, 4);
        return rtrim(rtrim(number_format($v, 4, ',', ''), '0'), ',') ?: '0';
    }
}
