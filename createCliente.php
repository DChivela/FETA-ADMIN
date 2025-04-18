<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "fetafacil");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura dos dados do formulário
    $bi           = $_POST['bi'] ?? null;
    $nome         = $_POST['nome'] ?? null;
    $genero       = $_POST['genero'] ?? null;
    $nascimento   = $_POST['Nascimento'] ?? null;
    $altura       = $_POST['altura'] ?? null;
    $tipo         = $_POST['tipo'] ?? null;
    $estado_civil = $_POST['estado_civil'] ?? null;
    $morada       = $_POST['morada'] ?? null;
    $provincia    = $_POST['provincia'] ?? null;
    $natural_de   = $_POST['natural_de'] ?? null;
    $filiacao     = $_POST['filiacao'] ?? null;
    $ocupacao     = $_POST['ocupacao'] ?? null;
    $nif          = $_POST['nif'] ?? null;
    $balanco      = $_POST['balanco'] ?? null;

    // Processa upload dos arquivos (caso enviados)
    $foto_bi = null;
    if (!empty($_FILES['foto_bi']['name'])) {
        $foto_bi = 'uploads/' . basename($_FILES['foto_bi']['name']);
        move_uploaded_file($_FILES['foto_bi']['tmp_name'], $foto_bi);
    }
    $img = null;
    if (!empty($_FILES['img']['name'])) {
        $img = 'uploads/' . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $img);
    }

    // Prepara a query de INSERT
    $sql = "INSERT INTO cliente (
                bi, nome, genero, nascimento, altura, tipo, estado_civil, morada, provincia, natural_de, filiacao, ocupacao, foto_bi, nif, balanco, img
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Todos os parâmetros são tratados como string (ajuste conforme necessário)
    $stmt->bind_param(
        "ssssssssssssssss",
        $bi,
        $nome,
        $genero,
        $nascimento,
        $altura,
        $tipo,
        $estado_civil,
        $morada,
        $provincia,
        $natural_de,
        $filiacao,
        $ocupacao,
        $foto_bi,
        $nif,
        $balanco,
        $img
    );

    if ($stmt->execute()) {
        header("Location: clientes.php");
        exit;
    } else {
        echo "Erro na inserção: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Exibe o formulário de criação (HTML pode ser incluído aqui ou em outro arquivo)
    echo "Acesse o formulário de criação.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="PT-pt">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Atualizar Cliente</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php include("nav.php"); ?>
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <br>
                        <div class="card">
                            <div class="card-body">
                                <form action="createCliente.php" method="POST" enctype="multipart/form-data">
                                    <!-- Grupo 1 -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nome">Nome</label>
                                            <input type="text" class="form-control" id="nome" name="nome" required value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="bi">BI</label>
                                            <input type="text" class="form-control" id="bi" name="bi" required value="">
                                        </div>
                                    </div>
                                    <!-- Grupo 2 -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="Nascimento">Data de Nascimento</label>
                                            <input type="date" class="form-control" id="Nascimento" name="Nascimento" required value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="genero">Gênero</label>
                                            <select class="form-control" id="genero" name="genero" required>
                                                <option value="Masculino" selected>Masculino</option>
                                                <option value="Feminino">Feminino</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Grupo 3 -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nif">NIF</label>
                                            <input type="text" class="form-control" id="nif" name="nif" required value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="estado_civil">Estado Civil</label>
                                            <input type="text" class="form-control" id="estado_civil" name="estado_civil" value="">
                                        </div>
                                    </div>
                                    <!-- Grupo 4 -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="provincia">Província</label>
                                            <input type="text" class="form-control" id="provincia" name="provincia" required value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="natural_de">Município</label>
                                            <input type="text" class="form-control" id="natural_de" name="natural_de" required value="">
                                        </div>
                                    </div>
                                    <!-- Grupo 5 -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="morada">Morada / Bairro</label>
                                            <input type="text" class="form-control" id="morada" name="morada" required value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="altura">Altura</label>
                                            <input type="number" class="form-control" id="altura" name="altura" required value="">
                                        </div>
                                    </div>
                                    <!-- Grupo 6 -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="ocupacao">Ocupação</label>
                                            <input type="text" class="form-control" id="ocupacao" name="ocupacao" required value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="balanco">Balanço</label>
                                            <input type="text" class="form-control" id="balanco" name="balanco" required value="">
                                        </div>
                                    </div>
                                    <!-- Grupo 7 -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="filiacao">Filiação</label>
                                            <input type="text" class="form-control" id="filiacao" name="filiacao" required value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="tipo">Tipo</label>
                                            <select class="form-control" id="tipo" name="tipo" required>
                                                <option value="Normal" selected>Cliente Normal</option>
                                                <option value="Empresa">Cliente Empresa</option>
                                                <option value="Agente">Cliente Agente</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Grupo 8 - Uploads -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="foto_bi">Foto do BI</label>
                                            <input type="file" class="form-control" id="foto_bi" name="foto_bi">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="img">Foto do Cliente</label>
                                            <input type="file" class="form-control" id="img" name="img">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right">Cadastrar cliente</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>
    <!-- Scripts -->
    <script src="assets/modules/jquery.min.js"></script>
    <script src="assets/modules/popper.js"></script>
    <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/stisla.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>