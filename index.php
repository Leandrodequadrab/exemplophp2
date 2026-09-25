<?php

declare(strict_types=1);

// ============================================================
// CONFIGURAÇÃO DO BANCO
// ============================================================

$host = 'database-1.cq3gi4g621yv.us-east-1.rds.amazonaws.com';
$port = '5432';
$dbname = 'aws';
$user = 'postgres';
$password = 'ZNDKsvVqEDw5XBT';


// ============================================================
// CONEXÃO COM O POSTGRESQL
// ============================================================

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

    die('
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Erro</title>
            <style>
                body {
                    margin: 0;
                    background: #f5f7fb;
                    font-family: Arial, sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                }

                .erro {
                    background: white;
                    padding: 30px;
                    border-radius: 16px;
                    box-shadow: 0 10px 30px rgba(0,0,0,.08);
                    max-width: 500px;
                    text-align: center;
                }

                .erro h1 {
                    color: #dc3545;
                    margin-top: 0;
                }

                .erro p {
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="erro">
                <h1>Erro de conexão</h1>
                <p>Não foi possível conectar ao banco de dados.</p>
            </div>
        </body>
        </html>
    ');

}


// ============================================================
// CONSULTA
// ============================================================

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

try {

    $stmt = $pdo->query($sql);
    $clientes = $stmt->fetchAll();

} catch (PDOException $e) {

    die('
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Erro</title>
        </head>

        <body style="
            font-family:Arial;
            background:#f5f7fb;
            padding:50px;
        ">

            <div style="
                max-width:600px;
                margin:auto;
                background:white;
                padding:30px;
                border-radius:15px;
            ">

                <h2 style="color:#dc3545;">
                    Erro ao consultar clientes
                </h2>

                <p>
                    Verifique se as tabelas existem no banco.
                </p>

            </div>

        </body>
        </html>
    ');

}


// ============================================================
// FUNÇÃO DE SEGURANÇA
// ============================================================

function e(?string $valor): string
{
    return htmlspecialchars(
        $valor ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}


// ============================================================
// ESTATÍSTICAS
// ============================================================

$totalClientes = count($clientes);

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

        /* =====================================================
           RESET
        ===================================================== */

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }


        /* =====================================================
           BODY
        ===================================================== */

        body {

            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #f8fafc 0%,
                    #eef2ff 100%
                );

            color: #1e293b;

            min-height: 100vh;

        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            color: white;

            padding: 28px 20px;

            box-shadow:
                0 4px 20px rgba(79, 70, 229, .25);

        }


        .header-content {

            max-width: 1400px;

            margin: auto;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

        }


        .logo {

            display: flex;

            align-items: center;

            gap: 14px;

        }


        .logo-icon {

            width: 48px;
            height: 48px;

            background:
                rgba(255,255,255,.15);

            border:
                1px solid rgba(255,255,255,.25);

            border-radius: 14px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 23px;

        }


        .logo h1 {

            font-size: 24px;

            font-weight: 700;

        }


        .logo p {

            margin-top: 3px;

            font-size: 13px;

            opacity: .8;

        }


        /* =====================================================
           CONTAINER
        ===================================================== */

        .container {

            max-width: 1400px;

            margin: 0 auto;

            padding: 30px 20px 50px;

        }


        /* =====================================================
           CARDS
        ===================================================== */

        .stats {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;

            margin-bottom: 25px;

        }


        .stat-card {

            background: white;

            border-radius: 18px;

            padding: 22px;

            box-shadow:
                0 8px 30px rgba(15, 23, 42, .06);

            border:
                1px solid #e2e8f0;

            display: flex;

            align-items: center;

            gap: 16px;

        }


        .stat-icon {

            width: 50px;
            height: 50px;

            border-radius: 14px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;

        }


        .stat-icon.blue {

            background: #eef2ff;

            color: #4f46e5;

        }


        .stat-icon.green {

            background: #ecfdf5;

            color: #059669;

        }


        .stat-icon.orange {

            background: #fff7ed;

            color: #ea580c;

        }


        .stat-label {

            font-size: 13px;

            color: #64748b;

            margin-bottom: 4px;

        }


        .stat-value {

            font-size: 25px;

            font-weight: 700;

            color: #0f172a;

        }


        /* =====================================================
           TABELA
        ===================================================== */

        .card {

            background: white;

            border-radius: 20px;

            border:
                1px solid #e2e8f0;

            box-shadow:
                0 10px 35px rgba(15, 23, 42, .07);

            overflow: hidden;

        }


        .card-header {

            padding: 22px 24px;

            border-bottom:
                1px solid #e2e8f0;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

        }


        .card-title {

            font-size: 18px;

            font-weight: 700;

            color: #0f172a;

        }


        .card-subtitle {

            font-size: 13px;

            color: #64748b;

            margin-top: 4px;

        }


        .badge {

            background: #eef2ff;

            color: #4f46e5;

            padding: 7px 12px;

            border-radius: 999px;

            font-size: 12px;

            font-weight: 600;

        }


        .table-wrapper {

            overflow-x: auto;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            min-width: 1100px;

        }


        thead {

            background: #f8fafc;

        }


        th {

            padding: 14px 18px;

            text-align: left;

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: .05em;

            color: #64748b;

            font-weight: 700;

            border-bottom:
                1px solid #e2e8f0;

        }


        td {

            padding: 17px 18px;

            border-bottom:
                1px solid #f1f5f9;

            font-size: 14px;

            color: #334155;

            vertical-align: middle;

        }


        tbody tr {

            transition:
                background .15s ease;

        }


        tbody tr:hover {

            background: #f8fafc;

        }


        tbody tr:last-child td {

            border-bottom: none;

        }


        /* =====================================================
           CLIENTE
        ===================================================== */

        .cliente {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .avatar {

            width: 40px;
            height: 40px;

            flex-shrink: 0;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #8b5cf6
                );

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 14px;

            font-weight: 700;

        }


        .cliente-nome {

            font-weight: 600;

            color: #0f172a;

        }


        .cliente-id {

            color: #94a3b8;

            font-size: 11px;

            margin-top: 2px;

        }


        /* =====================================================
           CONTATO
        ===================================================== */

        .email {

            color: #475569;

        }


        .telefone {

            font-weight: 500;

        }


        /* =====================================================
           ENDEREÇO
        ===================================================== */

        .endereco {

            line-height: 1.5;

        }


        .endereco-principal {

            color: #334155;

            font-weight: 500;

        }


        .endereco-secundario {

            color: #94a3b8;

            font-size: 12px;

            margin-top: 2px;

        }


        /* =====================================================
           ESTADO
        ===================================================== */

        .estado {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            background: #f1f5f9;

            color: #475569;

            border-radius: 7px;

            padding: 5px 8px;

            font-size: 11px;

            font-weight: 700;

        }


        /* =====================================================
           VAZIO
        ===================================================== */

        .empty {

            padding: 70px 20px;

            text-align: center;

        }


        .empty-icon {

            width: 70px;
            height: 70px;

            margin: auto auto 15px;

            border-radius: 20px;

            background: #f1f5f9;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 30px;

        }


        .empty h3 {

            color: #334155;

            margin-bottom: 7px;

        }


        .empty p {

            color: #94a3b8;

            font-size: 14px;

        }


        /* =====================================================
           RESPONSIVO
        ===================================================== */

        @media (max-width: 800px) {

            .stats {

                grid-template-columns: 1fr;

            }

            .header-content {

                align-items: flex-start;

            }

            .container {

                padding:
                    20px 12px 40px;

            }

            .header {

                padding: 22px 15px;

            }

            .logo h1 {

                font-size: 20px;

            }

        }

    </style>

</head>


<body>


<!-- ==========================================================
     HEADER
=========================================================== -->

<header class="header">

    <div class="header-content">

        <div class="logo">

            <div class="logo-icon">
                👥
            </div>

            <div>

                <h1>
                    Clientes
                </h1>

                <p>
                    Gerenciamento de clientes
                </p>

            </div>

        </div>

    </div>

</header>


<!-- ==========================================================
     CONTEÚDO
=========================================================== -->

<main class="container">


    <!-- ======================================================
         ESTATÍSTICAS
    ======================================================= -->

    <section class="stats">


        <div class="stat-card">

            <div class="stat-icon blue">
                👥
            </div>

            <div>

                <div class="stat-label">
                    Total de clientes
                </div>

                <div class="stat-value">
                    <?= $totalClientes ?>
                </div>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon green">
                ✓
            </div>

            <div>

                <div class="stat-label">
                    Cadastro
                </div>

                <div class="stat-value">
                    Ativo
                </div>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon orange">
                🗄️
            </div>

            <div>

                <div class="stat-label">
                    Banco de dados
                </div>

                <div class="stat-value">
                    PostgreSQL
                </div>

            </div>

        </div>


    </section>


    <!-- ======================================================
         LISTA
    ======================================================= -->

    <section class="card">


        <div class="card-header">

            <div>

                <div class="card-title">
                    Todos os clientes
                </div>

                <div class="card-subtitle">
                    Clientes cadastrados no sistema
                </div>

            </div>

            <div class="badge">
                <?= $totalClientes ?> registros
            </div>

        </div>


        <?php if (empty($clientes)): ?>


            <div class="empty">

                <div class="empty-icon">
                    👤
                </div>

                <h3>
                    Nenhum cliente encontrado
                </h3>

                <p>
                    Ainda não existem clientes cadastrados.
                </p>

            </div>


        <?php else: ?>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Cliente
                            </th>

                            <th>
                                E-mail
                            </th>

                            <th>
                                CPF
                            </th>

                            <th>
                                Telefone
                            </th>

                            <th>
                                Endereço
                            </th>

                            <th>
                                Cidade
                            </th>

                            <th>
                                Estado
                            </th>

                            <th>
                                Cadastro
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php foreach ($clientes as $cliente): ?>


                        <?php

                        $nome = trim(
                            $cliente['nome'] ?? ''
                        );

                        $iniciais = '';

                        $partes = preg_split(
                            '/\s+/',
                            $nome
                        );

                        if (!empty($partes[0])) {

                            $iniciais .=
                                mb_strtoupper(
                                    mb_substr(
                                        $partes[0],
                                        0,
                                        1
                                    )
                                );
                        }

                        if (
                            count($partes) > 1
                        ) {

                            $iniciais .=
                                mb_strtoupper(
                                    mb_substr(
                                        $partes[count($partes) - 1],
                                        0,
                                        1
                                    )
                                );
                        }

                        ?>


                        <tr>


                            <!-- CLIENTE -->

                            <td>

                                <div class="cliente">

                                    <div class="avatar">

                                        <?= e($iniciais) ?>

                                    </div>

                                    <div>

                                        <div class="cliente-nome">

                                            <?= e($nome) ?>

                                        </div>

                                        <div class="cliente-id">

                                            #<?= e(
                                                (string)
                                                $cliente['id']
                                            ) ?>

                                        </div>

                                    </div>

                                </div>

                            </td>


                            <!-- EMAIL -->

                            <td>

                                <div class="email">

                                    <?= e(
                                        $cliente['email']
                                    ) ?>

                                </div>

                            </td>


                            <!-- CPF -->

                            <td>

                                <?= e(
                                    $cliente['cpf']
                                ) ?: '—' ?>

                            </td>


                            <!-- TELEFONE -->

                            <td>

                                <div class="telefone">

                                    <?= e(
                                        $cliente['telefone']
                                    ) ?: '—' ?>

                                </div>

                            </td>


                            <!-- ENDEREÇO -->

                            <td>

                                <div class="endereco">

                                    <div class="endereco-principal">

                                        <?php if (
                                            !empty(
                                                $cliente['rua']
                                            )
                                        ): ?>

                                            <?= e(
                                                $cliente['rua']
                                            ) ?>

                                            <?php if (
                                                !empty(
                                                    $cliente['numero']
                                                )
                                            ): ?>

                                                ,
                                                <?= e(
                                                    $cliente['numero']
                                                ) ?>

                                            <?php endif; ?>

                                        <?php else: ?>

                                            —

                                        <?php endif; ?>

                                    </div>


                                    <?php if (
                                        !empty(
                                            $cliente['bairro']
                                        )
                                    ): ?>

                                        <div class="endereco-secundario">

                                            <?= e(
                                                $cliente['bairro']
                                            ) ?>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </td>


                            <!-- CIDADE -->

                            <td>

                                <?= e(
                                    $cliente['cidade']
                                ) ?: '—' ?>

                            </td>


                            <!-- ESTADO -->

                            <td>

                                <?php if (
                                    !empty(
                                        $cliente['estado']
                                    )
                                ): ?>

                                    <span class="estado">

                                        <?= e(
                                            $cliente['estado']
                                        ) ?>

                                    </span>

                                <?php else: ?>

                                    —

                                <?php endif; ?>

                            </td>


                            <!-- DATA -->

                            <td>

                                <?php

                                if (
                                    !empty(
                                        $cliente['criado_em']
                                    )
                                ) {

                                    echo e(
                                        date(
                                            'd/m/Y',
                                            strtotime(
                                                $cliente['criado_em']
                                            )
                                        )
                                    );

                                } else {

                                    echo '—';

                                }

                                ?>

                            </td>


                        </tr>


                    <?php endforeach; ?>


                    </tbody>

                </table>

            </div>


        <?php endif; ?>


    </section>


</main>


</body>

</html>
