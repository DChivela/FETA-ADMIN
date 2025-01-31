<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "fetafacil");

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura e valida os dados enviados pelo formulário
    $identificador = uniqid();
    $bi = $_POST['bi'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $nascimento = $_POST['Nascimento'] ?? null;
    $altura = $_POST['altura'] ?? null;
    $estado_civil = $_POST['estado_civil'] ?? null;
    $morada = $_POST['morada'] ?? null;
    $provincia = $_POST['provincia'] ?? null;
    $natural_de = $_POST['natural_de'] ?? null;
    $filiacao = $_POST['filiacao'] ?? null;
    $ocupacao = $_POST['ocupacao'] ?? null;
    $nif = $_POST['nif'] ?? null;
    $balanco = $_POST['balanco'] ?? null;

    // Upload de arquivos
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

    // Prepara a consulta SQL
    $sql = "INSERT INTO cliente (identificador, bi, nome, genero, nascimento, altura, estado_civil, morada, provincia, natural_de, filiacao, ocupacao, foto_bi, nif, balanco, img) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Associa os parâmetros
    $stmt->bind_param(
        "ssssssssssssssss",
        $identificador,
        $bi,
        $nome,
        $genero,
        $nascimento,
        $altura,
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

    // Executa a consulta
    if ($stmt->execute()) {
        echo "Registro inserido com sucesso!";
        // Redireciona para a página de clientes (descomente se necessário)
        header("Location: clientes.php");
        exit;
    } else {
        echo "Erro na inserção: " . $stmt->error;
    }

    // Fecha a declaração
    $stmt->close();
}

// Fecha a conexão
$conn->close();
?>



<!DOCTYPE html>
<html lang="PT-pt">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Inicio</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="assets/modules/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="assets/modules/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="assets/modules/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="assets/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="assets/components/slideImg/lightSlider.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <!-- nav include here-->
            <?php include("nav.php"); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <br>
                        <div class="card">
                            <div class="card-body">
                                <form action="Createcliente.php" method="POST" enctype="multipart/form-data">

                                    <!-- 1º Grupo -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nome">Nome</label>
                                            <input type="text" class="form-control" id="nome" name="nome" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="bi">BI</label>
                                            <input type="text" class="form-control" id="bi" name="bi" required>
                                        </div>
                                    </div>

                                    <!-- 2º Grupo -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="Nascimento">Data de Nascimento</label>
                                            <input type="date" class="form-control" id="Nascimento" name="Nascimento" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="genero">Gênero</label>
                                            <select class="form-control" id="genero" name="genero" required>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Feminino">Feminino</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 3º Grupo -->
                                    <div class="form-row">
                                    <div class="form-group col-md-6">
                                            <label for="nif">NIF</label>
                                            <input type="text" class="form-control" id="nif" name="nif" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="estado_civil">Estado Civil</label>
                                            <input type="text" class="form-control" id="estado_civil" name="estado_civil">
                                        </div>
                                    </div>

                                    <!-- 4º Grupo -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="provincia">Província</label>
                                            <input type="text" class="form-control" id="provincia" name="provincia" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="natural_de">Municipio</label>
                                            <input type="text" class="form-control" id="natural_de" name="natural_de" required>
                                        </div>
                                    </div>

                                    <!-- 5º Grupo -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="morada">Morada / Bairro</label>
                                            <input type="text" class="form-control" id="morada" name="morada" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="altura">Altura</label>
                                            <input type="number" class="form-control" id="altura" name="altura" required>
                                        </div>
                                    </div>

                                    <!-- 6º Grupo -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="ocupacao">Ocupação</label>
                                            <input type="text" class="form-control" id="ocupacao" name="ocupacao" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="balanco">Balanço</label>
                                            <input type="text" class="form-control" id="balanco" name="balanco" required>
                                        </div>
                                    </div>

                                    <!-- 7º Grupo -->
                                    <div class="form-row">
                                    <div class="form-group col-md-6">
                                            <label for="filiacao">Filiação</label>
                                            <input type="text" class="form-control" id="filiacao" name="filiacao" required>
                                        </div>
                                    </div>

                                    <!-- 9º Grupo -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="foto_bi">Foto do BI</label>
                                            <input type="file" class="form-control" id="foto_bi" name="foto_bi" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="img">Foto do Cliente</label>
                                            <input type="file" class="form-control" id="img" name="img" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right">Cadastrar cliente</button>
                                </form>

                            </div>
                        </div>
                    </div>


                </section>
            </div>
            <!-- footer include here -->
            <?php
            include("footer.php");
            ?>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="assets/modules/jquery.min.js"></script>
    <script src="assets/modules/popper.js"></script>
    <script src="assets/modules/tooltip.js"></script>
    <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="assets/modules/moment.min.js"></script>
    <script src="assets/js/stisla.js"></script>

    <!-- JS Libraies -->
    <script src="assets/modules/cleave-js/dist/cleave.min.js"></script>
    <script src="assets/modules/cleave-js/dist/addons/cleave-phone.us.js"></script>
    <script src="assets/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
    <script src="assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <script src="assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="assets/modules/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/modules/jquery-selectric/jquery.selectric.min.js"></script>
    <!-- Template JS File 
    -->
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>

</body>

<style>
</style>

</html>
<?php
