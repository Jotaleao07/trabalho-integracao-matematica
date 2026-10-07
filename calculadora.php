<?php use App\Helpers\LeitorMatriz; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calculadora de Álgebra Linear</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h1 class="mb-1">Calculadora de Álgebra Linear</h1>
    <p class="text-muted mb-4">Digite uma linha da matriz por linha, com os números separados por espaço.</p>

    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label fw-semibold" for="operacao">Operação</label>
            <select name="operacao" id="operacao" class="form-select">
                <?php
                $opcoes = [
                    'somar' => 'Soma (A + B)',
                    'subtrair' => 'Subtração (A − B)',
                    'multiplicar' => 'Multiplicação (A × B)',
                    'escalar' => 'Multiplicação por escalar (k × A)',
                    'transposta' => 'Transposta de A',
                    'determinante' => 'Determinante de A',
                    'sistema' => 'Sistema linear (A·x = B)',
                ];
                foreach ($opcoes as $valor => $rotulo): ?>
                    <option value="<?= $valor ?>" <?= $operacao === $valor ? 'selected' : '' ?>><?= $rotulo ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-semibold" for="matrizA">Matriz A</label>
                <textarea name="matrizA" id="matrizA" rows="5" class="form-control font-monospace"><?= htmlspecialchars($textoA) ?></textarea>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold" for="matrizB">Matriz B / vetor b (sistema)</label>
                <textarea name="matrizB" id="matrizB" rows="5" class="form-control font-monospace"><?= htmlspecialchars($textoB) ?></textarea>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" for="escalar">Escalar k</label>
                <input name="escalar" id="escalar" class="form-control" value="<?= htmlspecialchars($escalar) ?>">
            </div>
        </div>
        <button class="btn btn-primary mt-4 align-self-start">Calcular</button>
    </form>

    <?php if ($erro): ?>
        <div class="alert alert-danger mt-4"><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></div>
    <?php elseif ($resultado !== null): ?>
        <div class="card p-4 mt-4 shadow-sm">
            <h2 class="h5">Resultado</h2>
            <?php if (is_float($resultado) || is_int($resultado)): ?>
                <p class="display-6 mb-0">det(A) = <?= LeitorMatriz::numero($resultado) ?></p>
            <?php elseif ($operacao === 'sistema'): ?>
                <ul class="list-unstyled fs-5 mb-0">
                    <?php foreach ($resultado as $i => $x): ?>
                        <li>x<sub><?= $i + 1 ?></sub> = <?= LeitorMatriz::numero($x) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <table class="table table-bordered matriz w-auto mb-0">
                    <?php foreach ($resultado as $linha): ?>
                        <tr><?php foreach ($linha as $v): ?><td><?= LeitorMatriz::numero($v) ?></td><?php endforeach; ?></tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
