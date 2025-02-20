<?php

session_start();

// processa_levantamento.php

// 1. Recebe os dados do formulário via POST
$cliente_identificador = $_POST['cliente_identificador'];  // p.ex.: 12345
$transacao_pid         = $_POST['transacao_pid'];          // p.ex.: 54321
$agente                = $_POST['agente'];                 // p.ex.: Agente X

// Recebe os valores das notas (caso algum campo não seja preenchido, podemos atribuir 0)
$nota10   = isset($_POST['nota10'])   ? (int) $_POST['nota10']   : 0;
$nota20   = isset($_POST['nota20'])   ? (int) $_POST['nota20']   : 0;
$nota50   = isset($_POST['nota50'])   ? (int) $_POST['nota50']   : 0;
$nota100  = isset($_POST['nota100'])  ? (int) $_POST['nota100']  : 0;
$nota200  = isset($_POST['nota200'])  ? (int) $_POST['nota200']  : 0;
$nota500  = isset($_POST['nota500'])  ? (int) $_POST['nota500']  : 0;
$nota1000 = isset($_POST['nota1000']) ? (int) $_POST['nota1000'] : 0;
$nota2000 = isset($_POST['nota2000']) ? (int) $_POST['nota2000'] : 0;
$nota5000 = isset($_POST['nota5000']) ? (int) $_POST['nota5000'] : 0;

// O campo "total" a levantar:
$total    = isset($_POST['total'])    ? (float) $_POST['total']  : 0;

// 2. Formata os dados das notas num array e converte para JSON para guardar na coluna "notas"
// Podes também optar por guardar os valores separados em colunas diferentes se assim o desejares.
$notas_array = [
    'nota10'   => $nota10,
    'nota20'   => $nota20,
    'nota50'   => $nota50,
    'nota100'  => $nota100,
    'nota200'  => $nota200,
    'nota500'  => $nota500,
    'nota1000' => $nota1000,
    'nota2000' => $nota2000,
    'nota5000' => $nota5000
];
$notas = json_encode($notas_array);

// 3. Define a data/hora atual e extrai dia, mês e ano
$quando = date("Y-m-d H:i:s");
$dia    = date("d");
$mes    = date("m");
$ano    = date("Y");

// 4. Conecta à base de dados usando PDO
$host     = 'localhost';
$dbname   = 'fetafacil';
$usuario  = 'root';
$senha    = '';
$dsn      = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $pdo = new PDO($dsn, $usuario, $senha);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 5. Prepara a query de INSERT
    $sql = "INSERT INTO deposito (
                cliente_identificador, 
                transacao_pid, 
                agente, 
                notas, 
                total, 
                quando, 
                dia, 
                mes, 
                ano
            ) VALUES (
                :cliente_identificador, 
                :transacao_pid, 
                :agente, 
                :notas, 
                :total, 
                :quando, 
                :dia, 
                :mes, 
                :ano
            )";

    $stmt = $pdo->prepare($sql);

    // Liga os parâmetros
    $stmt->bindParam(':cliente_identificador', $cliente_identificador);
    $stmt->bindParam(':transacao_pid', $transacao_pid);
    $stmt->bindParam(':agente', $agente);
    $stmt->bindParam(':notas', $notas);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':quando', $quando);
    $stmt->bindParam(':dia', $dia);
    $stmt->bindParam(':mes', $mes);
    $stmt->bindParam(':ano', $ano);

    // Executa a query
    $stmt->execute();

    // Se tudo correr bem, podes redirecionar para outra página ou exibir uma mensagem de sucesso
    // Definir mensagem de sucesso na sessão
    $_SESSION['mensagem_sucesso'] = "deposito registrado com sucesso!";

    // Opcional: redirecionar após alguns segundos
    header("Location: index.php");
    exit(); // Encerrar o script após o redirecionamento

} catch (PDOException $e) {
    // Trata o erro (em ambiente de produção, evita mostrar detalhes sensíveis)
    echo "Erro ao efetuar o depósito: " . $e->getMessage();
}
?>
