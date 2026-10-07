<?php

namespace App\Models;

use InvalidArgumentException;
use RuntimeException;

/**
 * Resolve sistemas lineares A·x = b pela Eliminação de Gauss
 * com pivotamento parcial e substituição regressiva.
 */
class SistemaLinear
{
    private const EPSILON = 1e-10;

    /**
     * @param array $a matriz dos coeficientes (n x n)
     * @param array $b vetor dos termos independentes (n valores)
     * @return array solução [x1, x2, ...]
     */
    public static function resolver(array $a, array $b): array
    {
        Matriz::validar($a);
        $n = Matriz::linhas($a);
        if ($n !== Matriz::colunas($a)) {
            throw new InvalidArgumentException('A matriz dos coeficientes deve ser quadrada.');
        }
        if (count($b) !== $n) {
            throw new InvalidArgumentException('O vetor b deve ter o mesmo número de linhas da matriz.');
        }

        // monta a matriz aumentada [A | b]
        $m = [];
        for ($i = 0; $i < $n; $i++) {
            $m[$i] = array_merge(array_values($a[$i]), [(float) $b[$i]]);
        }

        // escalonamento
        for ($col = 0; $col < $n; $col++) {
            $pivo = $col;
            for ($i = $col + 1; $i < $n; $i++) {
                if (abs($m[$i][$col]) > abs($m[$pivo][$col])) {
                    $pivo = $i;
                }
            }
            if (abs($m[$pivo][$col]) < self::EPSILON) {
                continue; // coluna sem pivô: será analisada abaixo
            }
            [$m[$col], $m[$pivo]] = [$m[$pivo], $m[$col]];
            for ($i = $col + 1; $i < $n; $i++) {
                $fator = $m[$i][$col] / $m[$col][$col];
                for ($j = $col; $j <= $n; $j++) {
                    $m[$i][$j] -= $fator * $m[$col][$j];
                }
            }
        }

        // classificação: se falta pivô, o sistema é impossível ou indeterminado
        for ($i = 0; $i < $n; $i++) {
            if (abs($m[$i][$i]) < self::EPSILON) {
                foreach ($m as $linha) {
                    $coeficientesZerados = true;
                    for ($j = 0; $j < $n; $j++) {
                        if (abs($linha[$j]) > self::EPSILON) {
                            $coeficientesZerados = false;
                        }
                    }
                    if ($coeficientesZerados && abs($linha[$n]) > self::EPSILON) {
                        throw new RuntimeException('Sistema impossível (SI): não existe solução.');
                    }
                }
                throw new RuntimeException('Sistema possível e indeterminado (SPI): infinitas soluções.');
            }
        }

        // substituição regressiva
        $x = array_fill(0, $n, 0.0);
        for ($i = $n - 1; $i >= 0; $i--) {
            $soma = $m[$i][$n];
            for ($j = $i + 1; $j < $n; $j++) {
                $soma -= $m[$i][$j] * $x[$j];
            }
            $x[$i] = $soma / $m[$i][$i];
        }
        return $x;
    }
}
