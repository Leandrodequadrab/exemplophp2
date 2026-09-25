<?php

declare(strict_types=1);

// ================================
// CONFIGURAÇÃO DO BANCO
// ================================

$host = 'database-1.cq3gi4g621yv.us-east-1.rds.amazonaws.com';
$port = '5432';
$dbname = 'aws';
$user = 'postgres';
$password = 'ZNDKsvVqEDw5XBT';


// ================================
// CONEXÃO COM O POSTGRESQL
// ================================

try {
    $pdo = new PDO(
        "pgsql:host={$host};port={$port};dbname={$dbname}",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erro ao conectar ao banco de dados.');
}


// ================================
// CONSULTA
// ================================

$sql = "
    SELECT
        c.id,
        c.nome,
        c.email,
        c.cpf,
        c.data_nascimento,
        c.criado_em,

        e.rua,
        e.numero,
        e.bairro,
        e.cidade,
        e.estado,
        e.cep,

        t.numero AS telefone,
        t.tipo AS tipo_telefone

    FROM clientes c

    LEFT JOIN enderecos e
        ON e.cliente_id = c.id

    LEFT JOIN telefones t
        ON t.cliente_id = c.id

    ORDER BY c.id DESC
";

$stmt = $pdo->query($sql);
$clientes = $stmt->fetchAll();


// ================================
// FUNÇÃO PARA ESCAPAR HTML
// ================================

function e(?string $valor): string
{
    return htmlspecialchars(
        $valor ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Clientes</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 20px;
        }

        .tabela-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #343a40;
            color: #fff;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .sem-dados {
            padding: 30px;
            text-align: center;
            color: #777;
        }

        .contador {
            margin-bottom: 15px;
            color: #666;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Lista de Clientes</h1>

    <div class="contador">
        Total de clientes: <?= count($clientes) ?>
    </div>

    <div class="tabela-container">

        <?php if (empty($clientes)): ?>

            <div class="sem-dados">
                Nenhum cliente encontrado.
            </div>

        <?php else: ?>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>CPF</th>
                        <th>Nascimento</th>
                        <th>Telefone</th>
                        <th>Tipo</th>
                        <th>Endereço</th>
                        <th>Bairro</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>CEP</th>
                        <th>Criado em</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($clientes as $cliente): ?>

                    <tr>

                        <td>
                            <?= e((string) $cliente['id']) ?>
                        </td>

                        <td>
                            <?= e($cliente['nome']) ?>
                        </td>

                        <td>
                            <?= e($cliente['email']) ?>
                        </td>

                        <td>
                            <?= e($cliente['cpf']) ?>
                        </td>

                        <td>
                            <?= e($cliente['data_nascimento']) ?>
                        </td>

                        <td>
                            <?= e($cliente['telefone']) ?>
                        </td>

                        <td>
                            <?= e($cliente['tipo_telefone']) ?>
                        </td>

                        <td>
                            <?= e(
                                trim(
                                    ($cliente['rua'] ?? '') .
                                    ', ' .
                                    ($cliente['numero'] ?? '')
                                )
                            ) ?>
                        </td>

                        <td>
                            <?= e($cliente['bairro']) ?>
                        </td>

                        <td>
                            <?= e($cliente['cidade']) ?>
                        </td>

                        <td>
                            <?= e($cliente['estado']) ?>
                        </td>

                        <td>
                            <?= e($cliente['cep']) ?>
                        </td>

                        <td>
                            <?= e($cliente['criado_em']) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
