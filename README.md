# Calculadora de Álgebra Linear em PHP

Aplicação web simples em PHP que faz operações com matrizes e resolve sistemas lineares, com todos os algoritmos testados com PHPUnit.

## Requisitos
- PHP 8.4 ou superior
- Composer
- Laravel Herd (ou o servidor embutido do PHP)

## Instalação e execução
```bash
composer install
```
**Com Laravel Herd:** coloque a pasta do projeto dentro da pasta de sites do Herd e acesse `http://nome-da-pasta.test`.

**Sem Herd:** `php -S localhost:8000` e acesse `http://localhost:8000`.

Na página, digite cada linha da matriz em uma linha, com os números separados por espaço. Ex.:
```
1 2
3 4
```

## Algoritmos implementados
| Algoritmo | Arquivo |
|---|---|
| Soma de matrizes | `app/Models/Matriz.php` |
| Subtração de matrizes | `app/Models/Matriz.php` |
| Multiplicação de matrizes | `app/Models/Matriz.php` |
| Multiplicação por escalar | `app/Models/Matriz.php` |
| Transposta | `app/Models/Matriz.php` |
| Determinante (eliminação de Gauss) | `app/Models/Matriz.php` |
| Matriz identidade | `app/Models/Matriz.php` |
| Sistema linear (Gauss com pivotamento parcial) | `app/Models/SistemaLinear.php` |

## Estrutura (padrão MVC)
O projeto segue o padrão **MVC (Model-View-Controller)**:
- **Model** faz as contas e não sabe nada de HTML.
- **View** só mostra os dados.
- **Controller** liga os dois: lê o formulário, chama o Model e manda o resultado para a View.

```
index.php                 ponto de entrada (recebe a requisição)
app/Models/               Model: algoritmos (Matriz, SistemaLinear) - o que é testado
app/Controllers/          Controller: recebe o formulário e chama o Model
app/Views/                View: página HTML com Bootstrap
app/Helpers/              leitura do texto digitado e formatação de números
tests/                    testes PHPUnit
css/                      estilo
```

## Como rodar os testes
```bash
vendor/bin/phpunit --testdox
```
Resultado: **37 testes, 47 asserções, todos passando.**

Os testes cobrem casos felizes, casos de borda (matriz 1x1, identidade, nula), casos de erro (dimensões incompatíveis, matriz singular, sistema impossível e indeterminado) e precisão numérica com `assertEqualsWithDelta()`.

## Relatório de cobertura
Precisa da extensão Xdebug ou PCOV:
```bash
vendor/bin/phpunit --coverage-text
```
Resultado obtido:
```
Classes: 100.00% (2/2)
Methods: 100.00% (12/12)
Lines:   100.00% (112/112)
```
