<?php

namespace App\Models;

use InvalidArgumentException;

/**
 * Operações com matrizes.
 * Uma matriz é guardada como array de linhas: [[1, 2], [3, 4]].
 */
class Matriz
{
    /** Confere se a matriz é válida (não vazia e todas as linhas do mesmo tamanho). */
    public static function validar(array $m): void
    {
        if (count($m) === 0 || !is_array($m[0]) || count($m[0]) === 0) {
            throw new InvalidArgumentException('A matriz não pode ser vazia.');
        }
        $colunas = count($m[0]);
        foreach ($m as $linha) {
            if (!is_array($linha) || count($linha) !== $colunas) {
                throw new InvalidArgumentException('Todas as linhas devem ter o mesmo número de colunas.');
            }
            foreach ($linha as $valor) {
                if (!is_int($valor) && !is_float($valor)) {
                    throw new InvalidArgumentException('A matriz só pode conter números.');
                }
            }
        }
    }

    public static function linhas(array $m): int
    {
        return count($m);
    }

    public static function colunas(array $m): int
    {
        return count($m[0]);
    }

    /** A + B (mesmas dimensões). */
    public static function somar(array $a, array $b): array
    {
        self::mesmasDimensoes($a, $b);
        $r = [];
        foreach ($a as $i => $linha) {
            foreach ($linha as $j => $valor) {
                $r[$i][$j] = $valor + $b[$i][$j];
            }
        }
        return $r;
    }

    /** A - B (mesmas dimensões). */
    public static function subtrair(array $a, array $b): array
    {
        return self::somar($a, self::multiplicarEscalar($b, -1));
    }

    /** k * A. */
    public static function multiplicarEscalar(array $a, float $k): array
    {
        self::validar($a);
        $r = [];
        foreach ($a as $i => $linha) {
            foreach ($linha as $j => $valor) {
                $r[$i][$j] = $valor * $k;
            }
        }
        return $r;
    }

    /** A x B: colunas de A precisam ser iguais às linhas de B. */
    public static function multiplicar(array $a, array $b): array
    {
        self::validar($a);
        self::validar($b);
        if (self::colunas($a) !== self::linhas($b)) {
            throw new InvalidArgumentException('Dimensões incompatíveis: colunas de A devem ser iguais às linhas de B.');
        }
        $r = [];
        for ($i = 0; $i < self::linhas($a); $i++) {
            for ($j = 0; $j < self::colunas($b); $j++) {
                $soma = 0;
                for ($k = 0; $k < self::colunas($a); $k++) {
                    $soma += $a[$i][$k] * $b[$k][$j];
                }
                $r[$i][$j] = $soma;
            }
        }
        return $r;
    }

    /** Troca linhas por colunas. */
    public static function transposta(array $a): array
    {
        self::validar($a);
        $r = [];
        foreach ($a as $i => $linha) {
            foreach ($linha as $j => $valor) {
                $r[$j][$i] = $valor;
            }
        }
        return $r;
    }

    /** Determinante por eliminação de Gauss (só matriz quadrada). */
    public static function determinante(array $a): float
    {
        self::validar($a);
        $n = self::linhas($a);
        if ($n !== self::colunas($a)) {
            throw new InvalidArgumentException('O determinante só existe para matriz quadrada.');
        }
        $det = 1.0;
        for ($col = 0; $col < $n; $col++) {
            // procura o maior pivô da coluna (pivotamento parcial)
            $pivo = $col;
            for ($i = $col + 1; $i < $n; $i++) {
                if (abs($a[$i][$col]) > abs($a[$pivo][$col])) {
                    $pivo = $i;
                }
            }
            if (abs($a[$pivo][$col]) < 1e-12) {
                return 0.0; // coluna zerada -> matriz singular
            }
            if ($pivo !== $col) {
                [$a[$col], $a[$pivo]] = [$a[$pivo], $a[$col]];
                $det = -$det; // trocar linhas inverte o sinal
            }
            $det *= $a[$col][$col];
            for ($i = $col + 1; $i < $n; $i++) {
                $fator = $a[$i][$col] / $a[$col][$col];
                for ($j = $col; $j < $n; $j++) {
                    $a[$i][$j] -= $fator * $a[$col][$j];
                }
            }
        }
        return $det;
    }

    /** Cria a matriz identidade n x n. */
    public static function identidade(int $n): array
    {
        if ($n < 1) {
            throw new InvalidArgumentException('O tamanho deve ser pelo menos 1.');
        }
        $r = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $r[$i][$j] = $i === $j ? 1 : 0;
            }
        }
        return $r;
    }

    private static function mesmasDimensoes(array $a, array $b): void
    {
        self::validar($a);
        self::validar($b);
        if (self::linhas($a) !== self::linhas($b) || self::colunas($a) !== self::colunas($b)) {
            throw new InvalidArgumentException('As matrizes precisam ter as mesmas dimensões.');
        }
    }
}
