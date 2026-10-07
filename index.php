<?php

// Ponto de entrada: tudo passa por aqui (Front Controller).
require __DIR__ . '/vendor/autoload.php';

use App\Controllers\CalculadoraController;

$controller = new CalculadoraController();
$pagina = $controller->processar($_POST, $_SERVER['REQUEST_METHOD'] === 'POST');

// deixa $operacao, $textoA, $resultado, $erro... disponíveis para a View
extract($pagina);

require __DIR__ . '/app/Views/calculadora.php';
