<?php
// search.php
//O session deve estar acima de qualquer código, sendo assim a primeira linha para verificar a sessão do usuário
session_start();
if(!isset($_SESSION['feta-admin'])){
    // Caso não tenha sessão iniciada
    // leva direto na pagina inicial.
    header('Location: index.php');
    exit;
    }
// Verifica se o termo foi enviado
if (isset($_GET['termo'])) {
    $termo = htmlspecialchars($_GET['termo']); // Sanitiza o termo para evitar injeções de código

    // Conexão à base de dados
    $conn = new mysqli("localhost", "root", "", "fetafacil");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Query de pesquisa (ajustar para sua estrutura de tabelas)
    $sql = "SELECT * FROM cliente WHERE nome LIKE ? OR bi LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $likeTermo = "%$termo%";
    $stmt->bind_param("ss", $likeTermo, $likeTermo);
    $stmt->execute();

    $resultado = $stmt->get_result();

    // Armazena os resultados
    $dados = [];
    while ($row = $resultado->fetch_assoc()) {
        $dados[] = $row;
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();
} else {
    $dados = []; // Nenhum termo enviado
}
?>

<!-- Renderiza os resultados -->
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa</title>
    <link rel="stylesheet" href="path_para_o_css.css">
</head>
<body>
    <div class="container">
        <h1>Resultados da Pesquisa</h1>
        <?php if (!empty($dados)) : ?>
            <ul>
                <?php foreach ($dados as $dado) : ?>
                    <li>
                        <?= htmlspecialchars($dado['nome']) ?> - <?= htmlspecialchars($dado['bi']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Nenhum resultado encontrado para "<strong><?= htmlspecialchars($termo ?? '') ?></strong>".</p>
        <?php endif; ?>
    </div>
</body>
</html>
