<?php

namespace Testes;

use App\Models\Matriz;
use App\Models\SistemaLinear;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class SistemaLinearTest extends TestCase
{
    // ---------- casos felizes ----------
    public function testSistema2x2(): void
    {
        // x + y = 3 ; x - y = 1  ->  x = 2, y = 1
        $x = SistemaLinear::resolver([[1, 1], [1, -1]], [3, 1]);
        $this->assertEqualsWithDelta(2.0, $x[0], 1e-9);
        $this->assertEqualsWithDelta(1.0, $x[1], 1e-9);
    }

    public function testSistema3x3(): void
    {
        // solução conhecida: x = 2, y = 3, z = -1
        $a = [[2, 1, -1], [-3, -1, 2], [-2, 1, 2]];
        $b = [8, -11, -3];
        $x = SistemaLinear::resolver($a, $b);
        $this->assertEqualsWithDelta(2.0, $x[0], 1e-9);
        $this->assertEqualsWithDelta(3.0, $x[1], 1e-9);
        $this->assertEqualsWithDelta(-1.0, $x[2], 1e-9);
    }

    public function testSistemaQuePrecisaTrocarLinhas(): void
    {
        // primeiro coeficiente é zero
        $x = SistemaLinear::resolver([[0, 1], [1, 0]], [5, 7]);
        $this->assertEqualsWithDelta(7.0, $x[0], 1e-9);
        $this->assertEqualsWithDelta(5.0, $x[1], 1e-9);
    }

    public function testSolucaoComDecimais(): void
    {
        // 3x = 1  ->  x = 0,3333...
        $x = SistemaLinear::resolver([[3]], [1]);
        $this->assertEqualsWithDelta(1 / 3, $x[0], 1e-9);
    }

    // ---------- casos de borda ----------
    public function testSistema1x1(): void
    {
        $this->assertEqualsWithDelta([4.0], SistemaLinear::resolver([[2]], [8]), 1e-9);
    }

    public function testSistemaComIdentidade(): void
    {
        $x = SistemaLinear::resolver(Matriz::identidade(3), [4, 5, 6]);
        $this->assertEqualsWithDelta([4.0, 5.0, 6.0], $x, 1e-9);
    }

    public function testVetorBNuloDaSolucaoNula(): void
    {
        $x = SistemaLinear::resolver([[1, 2], [3, 4]], [0, 0]);
        $this->assertEqualsWithDelta([0.0, 0.0], $x, 1e-9);
    }

    // ---------- integração: A·x deve voltar a b ----------
    public function testSolucaoConfereComMultiplicacao(): void
    {
        $a = [[4, -2, 1], [3, 6, -4], [2, 1, 8]];
        $b = [12, -25, 32];
        $x = SistemaLinear::resolver($a, $b);
        $coluna = array_map(fn ($v) => [$v], $x);
        $produto = Matriz::multiplicar($a, $coluna);
        foreach ($b as $i => $valor) {
            $this->assertEqualsWithDelta($valor, $produto[$i][0], 1e-9);
        }
    }

    // ---------- casos de erro ----------
    public function testSistemaImpossivel(): void
    {
        // x + y = 2 ; x + y = 5  (retas paralelas)
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('impossível');
        SistemaLinear::resolver([[1, 1], [1, 1]], [2, 5]);
    }

    public function testSistemaIndeterminado(): void
    {
        // x + y = 2 ; 2x + 2y = 4  (mesma reta)
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('indeterminado');
        SistemaLinear::resolver([[1, 1], [2, 2]], [2, 4]);
    }

    public function testMatrizNulaEhIndeterminada(): void
    {
        $this->expectException(RuntimeException::class);
        SistemaLinear::resolver([[0, 0], [0, 0]], [0, 0]);
    }

    public function testMatrizNaoQuadradaGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SistemaLinear::resolver([[1, 2, 3], [4, 5, 6]], [1, 2]);
    }

    public function testVetorDeTamanhoErradoGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SistemaLinear::resolver([[1, 2], [3, 4]], [1, 2, 3]);
    }
}
