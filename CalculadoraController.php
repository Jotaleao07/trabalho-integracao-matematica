<?php

namespace App\Controllers;

use App\Helpers\LeitorMatriz;
use App\Models\Matriz;
use App\Models\SistemaLinear;
use InvalidArgumentException;
use Throwable;

/**
 * Controller: recebe os dados do formulário, chama o Model certo
 * e entrega para a View tudo o que ela precisa mostrar.
 */
class CalculadoraController
{
    public function processar(array $dados, bool $enviado): array
    {
        $operacao = $dados['operacao'] ?? 'somar';
        $textoA = $dados['matrizA'] ?? "1 2\n3 4";
        $textoB = $dados['matrizB'] ?? "5 6\n7 8";
        $escalar = $dados['escalar'] ?? '2';
        $resultado = null;
        $erro = null;

        if ($enviado) {
            try {
                $resultado = $this->calcular($operacao, $textoA, $textoB, $escalar);
            } catch (Throwable $e) {
                $erro = $e->getMessage();
            }
        }

        return compact('operacao', 'textoA', 'textoB', 'escalar', 'resultado', 'erro');
    }

    private function calcular(string $operacao, string $textoA, string $textoB, string $escalar): array|float
    {
        $a = LeitorMatriz::ler($textoA);

        return match ($operacao) {
            'somar'        => Matriz::somar($a, LeitorMatriz::ler($textoB)),
            'subtrair'     => Matriz::subtrair($a, LeitorMatriz::ler($textoB)),
            'multiplicar'  => Matriz::multiplicar($a, LeitorMatriz::ler($textoB)),
            'escalar'      => Matriz::multiplicarEscalar($a, (float) str_replace(',', '.', $escalar)),
            'transposta'   => Matriz::transposta($a),
            'determinante' => Matriz::determinante($a),
            'sistema'      => SistemaLinear::resolver($a, LeitorMatriz::lerVetor($textoB)),
            default        => throw new InvalidArgumentException('Operação desconhecida.'),
        };
    }
}
