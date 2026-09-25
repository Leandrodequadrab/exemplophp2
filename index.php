<?php

declare(strict_types=1);

// ============================================================
// DEBUG
// ============================================================

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);


// ============================================================
// CONFIGURAÇÃO DO BANCO
// ============================================================

$host = 'database-1.cq3gi4g621yv.us-east-1.rds.amazonaws.com';
$port = '5432';
$dbname = 'aws';
$user = 'postgres';
$password = 'ZNDKsvVqEDw5XBT';


// ============================================================
// FUNÇÃO DE DEBUG
// ============================================================

function debug(string $titulo, mixed $valor = null): void
{
    echo '<div style="
        background:#111;
        color:#00ff66;
        padding:10px 15px;
        margin:5px 0;
        font-family:monospace;
        border-radius:5px;
        white-space:pre-wrap;
    ">';

    echo '<strong>[DEBUG] ' . htmlspecialchars($titulo) . '</strong>';

    if ($valor !== null) {
        echo "\n";
        echo htmlspecialchars(
            print_r($valor, true)
        );
    }

    echo '</div>';
}


// ============================================================
// INÍCIO
// ============================================================

echo '<h2>Debug da conexão PostgreSQL</h2>';

debug('PHP', PHP_VERSION);
debug('Sistema', PHP_OS);


// ============================================================
// VERIFICA EXTENSÃO PDO
// ============================================================

debug(
    'PDO disponível?',
    extension_loaded('pdo') ? 'SIM' : 'NÃO'
);

debug(
    'PDO PostgreSQL disponível?',
    extension_loaded('pdo_pgsql') ? 'SIM' : 'NÃO'
);

if (!extension_loaded('pdo_pgsql')) {

    die('
        <div style="
            background:#ffdddd;
            color:#900;
            padding:20px;
            margin:20px 0;
        ">
            <strong>ERRO:</strong>
            A extensão pdo_pgsql não está habilitada.
        </div>
    ');
}


// ============================================================
// TESTE DNS
// ============================================================

debug('Host PostgreSQL', $host);

$ip = gethostbyname($host);

debug('IP resolvido', $ip);

if ($ip === $host) {

    debug(
        'DNS',
        'ERRO: não foi possível resolver o hostname.'
    );

} else {

    debug(
        'DNS',
        'OK'
    );
}


// ============================================================
// TESTE DE PORTA
// ============================================================

debug(
    'Testando porta',
    $host . ':' . $port
);

$socket = @fsockopen(
    $host,
    (int) $port,
    $errno,
    $errstr,
    5
);

if ($socket) {

    debug(
        'Porta PostgreSQL',
        'OK - porta 5432 acessível'
    );

    fclose($socket);

} else {

    debug(
        'Porta PostgreSQL',
        "ERRO\nCódigo: {$errno}\nMensagem: {$errstr}"
    );
}


// ============================================================
// CONEXÃO PDO
// ============================================================

debug('Iniciando conexão PDO...');

try {

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

    debug('DSN', $dsn);

    $pdo = new PDO(
        $dsn,
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    debug(
        'CONEXÃO',
        'OK - conectado ao PostgreSQL!'
    );

} catch (PDOException $e) {

    debug(
        'ERRO PDO',
        [
            'mensagem' => $e->getMessage(),
            'codigo' => $e->getCode(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
        ]
    );

    echo '<pre style="
        background:#300;
        color:#fff;
        padding:20px;
        border-radius:5px;
    ">';

    echo htmlspecialchars(
        $e->getTraceAsString()
    );

    echo '</pre>';

    die();
}


// ============================================================
// TESTE SIMPLES NO BANCO
// ============================================================

debug(
    'Executando SELECT 1...'
);

try {

    $teste = $pdo->query('SELECT 1')->fetchColumn();

    debug(
        'SELECT 1',
        $teste
    );

} catch (PDOException $e) {

    debug(
        'ERRO SELECT 1',
        $e->getMessage()
    );

    die();
}


// ============================================================
// INFORMAÇÕES DO POSTGRESQL
// ============================================================

try {

    $versao = $pdo
        ->query('SELECT version()')
        ->fetchColumn();

    debug(
        'Versão PostgreSQL',
        $versao
    );

} catch (PDOException $e) {

    debug(
        'ERRO ao consultar versão',
        $e->getMessage()
    );
}


// ============================================================
// CONSULTA DOS CLIENTES
// ============================================================

debug(
    'Executando consulta dos clientes...'
);

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

    debug(
        'Consulta',
        'OK'
    );

    debug(
        'Quantidade de registros',
        count($clientes)
    );

} catch (PDOException $e) {

    debug(
        'ERRO NA CONSULTA SQL',
        [
            'mensagem' => $e->getMessage(),
            'codigo' => $e->getCode(),
        ]
    );

    debug(
        'SQL executado',
        $sql
    );

    die();
}


// ============================================================
// FUNÇÃO HTML
// ============================================================

function e(?string $valor): string
{
    return htmlspecialchars(
        $valor ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<hr>

<h1>Lista de Clientes</h1>

<div style="
    margin-bottom:20px;
    font-family:Arial;
">
    Total de clientes:
    <strong><?= count($clientes) ?></strong>
</div>


<?php if (empty($clientes)): ?>

    <div style="
        padding:20px;
        background:#fff3cd;
        color:#856404;
        border:1px solid #ffeeba;
    ">
        Nenhum cliente encontrado.
    </div>

<?php else: ?>

    <table style="
        width:100%;
        border-collapse:collapse;
        font-family:Arial;
    ">

        <thead>

            <tr style="
                background:#343a40;
                color:white;
            ">

                <th style="padding:10px;">ID</th>
                <th style="padding:10px;">Nome</th>
                <th style="padding:10px;">E-mail</th>
                <th style="padding:10px;">CPF</th>
                <th style="padding:10px;">Nascimento</th>
                <th style="padding:10px;">Telefone</th>
                <th style="padding:10px;">Tipo</th>
                <th style="padding:10px;">Endereço</th>
                <th style="padding:10px;">Bairro</th>
                <th style="padding:10px;">Cidade</th>
                <th style="padding:10px;">Estado</th>
                <th style="padding:10px;">CEP</th>
                <th style="padding:10px;">Criado em</th>

            </tr>

        </thead>

        <tbody>

        <?php foreach ($clientes as $cliente): ?>

            <tr>

                <td style="padding:10px;">
                    <?= e((string) $cliente['id']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['nome']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['email']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['cpf']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['data_nascimento']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['telefone']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['tipo_telefone']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e(
                        trim(
                            ($cliente['rua'] ?? '') .
                            ', ' .
                            ($cliente['numero'] ?? '')
                        )
                    ) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['bairro']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['cidade']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['estado']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['cep']) ?>
                </td>

                <td style="padding:10px;">
                    <?= e($cliente['criado_em']) ?>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>
