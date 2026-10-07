<?php

namespace Testes;

use App\Models\Matriz;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MatrizTest extends TestCase
{
    // ---------- casos felizes ----------
    public function testSomaDeDuasMatrizes2x2(): void
    {
        $this->assertSame([[6, 8], [10, 12]], Matriz::somar([[1, 2], [3, 4]], [[5, 6], [7, 8]]));
    }

    public function testSubtracao(): void
    {
        $this->assertEquals([[4, 4], [4, 4]], Matriz::subtrair([[5, 6], [7, 8]], [[1, 2], [3, 4]]));
    }

    public function testMultiplicacao2x2(): void
    {
        $this->assertSame([[19, 22], [43, 50]], Matriz::multiplicar([[1, 2], [3, 4]], [[5, 6], [7, 8]]));
    }

    public function testMultiplicacao2x3Por3x2(): void
    {
        $a = [[1, 2, 3], [4, 5, 6]];
        $b = [[7, 8], [9, 10], [11, 12]];
        $this->assertSame([[58, 64], [139, 154]], Matriz::multiplicar($a, $b));
    }

    public function testMultiplicacaoPorEscalar(): void
    {
        $this->assertEquals([[2, 4], [6, 8]], Matriz::multiplicarEscalar([[1, 2], [3, 4]], 2));
    }

    public function testTransposta(): void
    {
        $this->assertSame([[1, 4], [2, 5], [3, 6]], Matriz::transposta([[1, 2, 3], [4, 5, 6]]));
    }

    public function testDeterminante2x2(): void
    {
        $this->assertEqualsWithDelta(-2.0, Matriz::determinante([[1, 2], [3, 4]]), 1e-9);
    }

    public function testDeterminante3x3(): void
    {
        $a = [[2, -3, 1], [2, 0, -1], [1, 4, 5]];
        $this->assertEqualsWithDelta(49.0, Matriz::determinante($a), 1e-9);
    }

    public function testDeterminanteComTrocaDeLinha(): void
    {
        // primeiro elemento é zero, obriga a trocar linhas
        $this->assertEqualsWithDelta(-1.0, Matriz::determinante([[0, 1], [1, 0]]), 1e-9);
    }

    // ---------- casos de borda ----------
    public function testMatriz1x1(): void
    {
        $this->assertSame([[7]], Matriz::somar([[3]], [[4]]));
        $this->assertEqualsWithDelta(5.0, Matriz::determinante([[5]]), 1e-9);
    }

    public function testIdentidade(): void
    {
        $this->assertSame([[1, 0, 0], [0, 1, 0], [0, 0, 1]], Matriz::identidade(3));
    }

    public function testMultiplicarPelaIdentidadeNaoMuda(): void
    {
        $a = [[1, 2], [3, 4]];
        $this->assertSame($a, Matriz::multiplicar($a, Matriz::identidade(2)));
    }

    public function testDeterminanteDaIdentidadeEhUm(): void
    {
        $this->assertEqualsWithDelta(1.0, Matriz::determinante(Matriz::identidade(4)), 1e-9);
    }

    public function testMatrizNula(): void
    {
        $nula = [[0, 0], [0, 0]];
        $this->assertSame([[1, 2], [3, 4]], Matriz::somar([[1, 2], [3, 4]], $nula));
        $this->assertEqualsWithDelta(0.0, Matriz::determinante($nula), 1e-9);
    }

    public function testDeterminanteDeMatrizSingular(): void
    {
        $this->assertEqualsWithDelta(0.0, Matriz::determinante([[1, 2], [2, 4]]), 1e-9);
    }

    public function testTranspostaDaTranspostaVoltaAoOriginal(): void
    {
        $a = [[1, 2, 3], [4, 5, 6]];
        $this->assertSame($a, Matriz::transposta(Matriz::transposta($a)));
    }

    // ---------- precisão numérica ----------
    public function testPrecisaoComDecimais(): void
    {
        $r = Matriz::somar([[0.1]], [[0.2]]);
        $this->assertEqualsWithDelta(0.3, $r[0][0], 1e-9);
    }

    // ---------- casos de erro ----------
    public function testSomaComDimensoesDiferentesGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::somar([[1, 2]], [[1], [2]]);
    }

    public function testMultiplicacaoIncompativelGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::multiplicar([[1, 2, 3]], [[1, 2, 3]]);
    }

    public function testDeterminanteDeMatrizNaoQuadradaGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::determinante([[1, 2, 3], [4, 5, 6]]);
    }

    public function testMatrizVaziaGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::transposta([]);
    }

    public function testLinhasDeTamanhosDiferentesGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::transposta([[1, 2], [3]]);
    }

    public function testValorNaoNumericoGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::transposta([[1, 'a']]);
    }

    public function testIdentidadeDeTamanhoZeroGeraErro(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::identidade(0);
    }
}
