<?php

// Tempo máximo de processamento pesado, em segundos.
$duracao = isset($_GET['tempo']) ? (int) $_GET['tempo'] : 10;

// Limite de segurança.
$duracao = max(1, min($duracao, 30));

$inicio = microtime(true);
$iteracoes = 0;
$resultado = 0.0;

while ((microtime(true) - $inicio) < $duracao) {

    // Operações matemáticas deliberadamente pesadas.
    for ($i = 1; $i <= 100000; $i++) {
        $resultado += sqrt($i)
                    * sin($i)
                    * cos($i)
                    * log($i + 1);
    }

    $iteracoes++;
}

$tempoTotal = microtime(true) - $inicio;

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste de CPU - PHP</title>
</head>
<body>

<h1>Teste concluído</h1>

<p><strong>Tempo:</strong> <?= number_format($tempoTotal, 2) ?> segundos</p>
<p><strong>Blocos processados:</strong> <?= number_format($iteracoes) ?></p>
<p><strong>PID do PHP:</strong> <?= getmypid() ?></p>

</body>
</html>
