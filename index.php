<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (isset($_SESSION['feta-admin'])) {

    include "banco.php";
    $metadata = $_SESSION['metadata'];

    try {
        // Consulta para obter todos os administradores
        $query = $conn->prepare("SELECT * FROM `Administrador`");
        $query->execute();
        $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

        // Consulta para gerar o JSON com id e nome
        $consulta = $conn->query("SELECT `id`, `nome` FROM `Administrador`");
        $dados = array();
        while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $dados[] = $row;
        }
        $dados_json = json_encode($dados);
    } catch (PDOException $e) {
        echo "Erro na consulta: " . $e->getMessage();
        exit;
    }

    // Fecha a conexão
    $conn = null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="components/searchbar.css">

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

    <style>
        .resultado_pesquisa_cls {
            width: 80%;
            margin: 0 auto;
            max-width: 200px;
            height: 200px;
            overflow-y: auto;
            position: absolute;
            top: 0;
        }
        .custom_li {
            width: 100%;
            margin: 0 auto;
            border-bottom: 1px solid gray;
            list-style: none;
        }
        body {
            margin-bottom: 50px;
        }
    </style>
    <title>Home</title>

</head>
<body>

    <main class="main">
    <?php include("nav.php"); ?>

    </main>
    <script>
        var dadosUsuario = <?php echo $dados_json; ?>;
    </script>
</body>
</html>
<?php 
} else { 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <title>FETA-FÁCIL</title>
</head>
<body>
    <form action="./Administrador/entrar.php" method="post">
        <section class="vh-100" style="background-color: #ffffff;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card shadow-2-strong" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <h3 class="mb-5">Login</h3>
                                <div class="form-outline mb-4">
                                    <input type="email" id="typeEmailX-2" name="email" class="form-control form-control-lg" placeholder="Email" required />
                                </div>
                                <div class="form-outline mb-4">
                                    <input type="password" id="typePasswordX-2" name="passe" class="form-control form-control-lg" placeholder="Palavra passe" required />
                                </div>
                                <div class="form-outline mb-4">
                                    <a href="receber_codigo.php" style="color:red;text-decoration:none;">
                                        <p>Esqueci a passe</p>
                                    </a>
                                </div>
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Entrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
<?php 
}
?>
