<?php
session_start();

/*
|--------------------------------------------------------------------------
| CPU CLICKER - Jogo PHP em um único arquivo
|--------------------------------------------------------------------------
| PHP + HTML + CSS + JavaScript no mesmo arquivo.
*/

// Inicializa o jogo
if (!isset($_SESSION['jogo'])) {
    $_SESSION['jogo'] = [
        'pontos' => 0,
        'nivel' => 1,
        'poder' => 1,
        'cliques' => 0,
        'upgrades' => 0
    ];
}

$jogo = &$_SESSION['jogo'];

/*
|--------------------------------------------------------------------------
| Processamento proposital no servidor
|--------------------------------------------------------------------------
| Faz cálculos matemáticos para que cada clique gere algum trabalho
| real no PHP/Apache.
*/
function processamentoCPU($intensidade = 1)
{
    $resultado = 0;

    // Mantemos um limite para não travar o servidor.
    $limite = min(50000 * $intensidade, 500000);

    for ($i = 1; $i <= $limite; $i++) {
        $resultado += sqrt($i) * sin($i) * cos($i);
    }

    return $resultado;
}


// -------------------------------------------------------
// AÇÕES DO JOGO
// -------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';

    // ATACAR CPU
    if ($acao === 'processar') {

        $inicio = microtime(true);

        processamentoCPU($jogo['nivel']);

        $tempo = microtime(true) - $inicio;

        $ganho = $jogo['poder'] * $jogo['nivel'];

        $jogo['pontos'] += $ganho;
        $jogo['cliques']++;

        // Level up automático
        $nivelNecessario = $jogo['nivel'] * 20;

        if ($jogo['cliques'] >= $nivelNecessario) {
            $jogo['nivel']++;
            $jogo['cliques'] = 0;
        }

        $_SESSION['ultimo_tempo'] = $tempo;
    }


    // COMPRAR UPGRADE
    if ($acao === 'upgrade') {

        $preco = 50 * ($jogo['upgrades'] + 1);

        if ($jogo['pontos'] >= $preco) {

            $jogo['pontos'] -= $preco;
            $jogo['poder']++;
            $jogo['upgrades']++;

            $_SESSION['mensagem'] = "Upgrade comprado!";
        } else {
            $_SESSION['mensagem'] = "Pontos insuficientes!";
        }
    }


    // RESET
    if ($acao === 'reset') {

        $_SESSION['jogo'] = [
            'pontos' => 0,
            'nivel' => 1,
            'poder' => 1,
            'cliques' => 0,
            'upgrades' => 0
        ];

        $_SESSION['ultimo_tempo'] = 0;
        $_SESSION['mensagem'] = "Jogo reiniciado!";

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$tempo = $_SESSION['ultimo_tempo'] ?? 0;
$mensagem = $_SESSION['mensagem'] ?? '';

unset($_SESSION['mensagem']);

$precoUpgrade = 50 * ($jogo['upgrades'] + 1);

$progressoNecessario = $jogo['nivel'] * 20;

$porcentagem = ($jogo['cliques'] / $progressoNecessario) * 100;

?>
<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<title>CPU Clicker</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;
    min-height: 100vh;

    display: flex;
    justify-content: center;
    align-items: center;

    background:
        radial-gradient(circle at top, #172554, #020617 60%);

    color: white;

    font-family:
        Arial,
        Helvetica,
        sans-serif;
}

.game {

    width: 700px;

    background: rgba(15, 23, 42, 0.95);

    border: 1px solid #334155;

    border-radius: 20px;

    padding: 35px;

    box-shadow:
        0 20px 60px rgba(0,0,0,.5);
}

h1 {

    text-align: center;

    font-size: 42px;

    margin-top: 0;

    color: #38bdf8;
}

.subtitle {

    text-align: center;

    color: #94a3b8;

    margin-bottom: 30px;
}

.stats {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 10px;

    margin-bottom: 30px;
}

.stat {

    background: #1e293b;

    padding: 15px;

    border-radius: 12px;

    text-align: center;
}

.stat strong {

    display: block;

    color: #38bdf8;

    font-size: 23px;

    margin-top: 7px;
}

.cpu-area {

    text-align: center;

    margin: 30px 0;
}

.cpu {

    width: 180px;
    height: 180px;

    border-radius: 25px;

    border: 5px solid #38bdf8;

    background:
        linear-gradient(145deg, #1e293b, #0f172a);

    color: white;

    font-size: 30px;

    font-weight: bold;

    cursor: pointer;

    box-shadow:
        0 0 30px rgba(56,189,248,.3);

    transition: .15s;
}

.cpu:hover {

    transform: scale(1.05);

    box-shadow:
        0 0 50px rgba(56,189,248,.6);
}

.cpu:active {

    transform: scale(.92);
}

.progress-container {

    margin-top: 25px;
}

.progress {

    height: 15px;

    background: #334155;

    border-radius: 20px;

    overflow: hidden;
}

.progress-bar {

    height: 100%;

    width: <?= $porcentagem ?>%;

    background:
        linear-gradient(
            90deg,
            #38bdf8,
            #22c55e
        );

    transition: width .3s;
}

.level-text {

    color: #94a3b8;

    font-size: 13px;

    margin-top: 7px;

    text-align: right;
}

.actions {

    display: flex;

    gap: 15px;

    margin-top: 30px;
}

.actions form {

    flex: 1;
}

button.action {

    width: 100%;

    border: 0;

    padding: 15px;

    border-radius: 10px;

    cursor: pointer;

    font-size: 15px;

    font-weight: bold;
}

.upgrade {

    background: #22c55e;

    color: #052e16;
}

.upgrade:hover {

    background: #4ade80;
}

.reset {

    background: #ef4444;

    color: white;
}

.reset:hover {

    background: #f87171;
}

.server {

    margin-top: 30px;

    background: #020617;

    padding: 15px;

    border-radius: 10px;

    color: #94a3b8;

    font-family: monospace;

    font-size: 13px;
}

.message {

    text-align: center;

    margin: 15px 0;

    color: #facc15;

    font-weight: bold;
}

</style>

</head>

<body>

<div class="game">

    <h1>⚡ CPU CLICKER</h1>

    <div class="subtitle">
        Faça seu servidor trabalhar e acumule poder computacional.
    </div>


    <div class="stats">

        <div class="stat">
            Pontos
            <strong>
                <?= number_format($jogo['pontos']) ?>
            </strong>
        </div>

        <div class="stat">
            Nível
            <strong>
                <?= $jogo['nivel'] ?>
            </strong>
        </div>

        <div class="stat">
            Poder
            <strong>
                x<?= $jogo['poder'] ?>
            </strong>
        </div>

        <div class="stat">
            Upgrades
            <strong>
                <?= $jogo['upgrades'] ?>
            </strong>
        </div>

    </div>


    <?php if ($mensagem): ?>

        <div class="message">
            <?= htmlspecialchars($mensagem) ?>
        </div>

    <?php endif; ?>


    <div class="cpu-area">

        <form method="POST">

            <input
                type="hidden"
                name="acao"
                value="processar"
            >

            <button
                class="cpu"
                type="submit"
            >
                CPU
                <br>
                ⚡
            </button>

        </form>

    </div>


    <div class="progress-container">

        <div class="progress">

            <div class="progress-bar"></div>

        </div>

        <div class="level-text">

            <?= $jogo['cliques'] ?>
            /
            <?= $progressoNecessario ?>

            processamentos para o próximo nível

        </div>

    </div>


    <div class="actions">

        <form method="POST">

            <input
                type="hidden"
                name="acao"
                value="upgrade"
            >

            <button
                class="action upgrade"
                type="submit"
            >
                ⬆ Upgrade de CPU
                (<?= number_format($precoUpgrade) ?> pontos)
            </button>

        </form>


        <form method="POST">

            <input
                type="hidden"
                name="acao"
                value="reset"
            >

            <button
                class="action reset"
                type="submit"
            >
                Reiniciar jogo
            </button>

        </form>

    </div>


    <div class="server">

        SERVIDOR: Apache/PHP
        <br>

        PID PHP:
        <?= getmypid() ?>

        <br>

        Último processamento:
        <?= number_format($tempo, 5) ?> segundos

        <br>

        Intensidade atual:
        <?= number_format(min(50000 * $jogo['nivel'], 500000)) ?>
        operações

    </div>

</div>

</body>

</html>
