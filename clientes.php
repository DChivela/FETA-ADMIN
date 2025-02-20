<!-- inicialize the db -->
<?php

// Conexão à base de dados
$conn = new mysqli("localhost", "root", "", "fetafacil");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$limite = 5;
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;


// Query para obter o total de clientes
$sqlTotal = "SELECT COUNT(*) AS total FROM cliente";
$stmt = $conn->prepare($sqlTotal);
$stmt->execute();
$stmt->bind_result($totalClientes);
$stmt->fetch();
$stmt->close();

// Query de pesquisa (ajustar para sua estrutura de tabelas)
$sql = "SELECT * FROM cliente";
$resultado = $conn->query($sql);

// Fecha a conexão
$conn->close();

session_start();

?>
<!DOCTYPE html>
<html lang="PT-pt">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Clientes</title>

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
                        <style>
                            a {
                                color: #aaa;
                            }

                            a:hover {
                                text-decoration: none
                            }
                        </style>
                        <br>
                        <!-- ROW 1 -->
                        <div class="container">
                            <div class="row ">
                                <div class="col-8">
                                    <div class="container card">
                                        <!-- Detalhes do Cliente -->
                                        <h1> Lista de Clientes</h1>
                                        <div class="card-body" style="overflow-y: auto;">


                                            <?php
                                            if ($resultado->num_rows > 0): ?>
                                                <table class="table table-bordered table-hover table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>Nome</th>
                                                            <th>Género</th>
                                                            <th>BI</th>
                                                            <th>Acções</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($cliente = $resultado->fetch_assoc()): ?>
                                                            <tr>
                                                                <td><a id="nome" href="cliente.php?id=<?= htmlspecialchars($cliente['identificador']) ?>"><?= htmlspecialchars($cliente['nome']) ?></a></td>
                                                                <td><?= htmlspecialchars($cliente['genero']) ?></td>
                                                                <td><?= htmlspecialchars($cliente['bi']) ?></td>
                                                                <td>
                                                                    <a href="updateCliente.php?id=<?= htmlspecialchars($cliente['identificador']) ?>"
                                                                        class="btn btn-warning">Editar
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <p>Nenhum cliente encontrado</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label></label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="info-display">
                                                <div class="info-item" style="font-weight:bold;"> <span
                                                        class="info-icon">&#9432;</span>
                                                    <span>Todos:</span> <span
                                                        style="float:right;margin-top: 12px;"><?php echo $totalClientes; ?></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <a class="btn btn-lg btn-primary form-control" href="createCliente.php">Cadastrar Clientes</a>
                                </div>
                            </div>
                        </div>

                        <!-- /ROW 1 -->


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
<!-- Modal -->
<div class="modal fade" id="confirmar" tabindex="-1" role="dialog" aria-labelledby="confirmarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h1>Confirmar
                    levantamento</h1>
                <button type="button" class="btn btn-primary form-control">Confirmar por aplicativo</button> <br><br>
                <button type="button" class="btn btn-info form-control">Confirmar por USSD</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<style>
    .info-display {
        border-radius: 10px;
        background-color: #fff;
    }

    .info-item {
        padding: 10px 0;
        font-size: 1.2rem;
    }

    .info-item:not(:last-child) {
        border-bottom: 1px solid #ddd;
    }

    .info-icon {
        font-size: 1.5em;
        margin-right: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group input {
        font-size: 2rem;
        text-align: center;
        border: 2px solid #ced4da;
        border-radius: 0.25rem;
        padding: 10px;
    }

    .erro-info span {
        font-size: 3.5rem;
    }

    #nome {
        color: #333;
    }

    #nome {
        color: rgb(71, 73, 73);
        font-weight: 700;
    }

    #nome:hover {
        color: #2c72ce;
        font-weight: 700;
    }
</style>

</html>
<?php
