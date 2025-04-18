<!-- inicialize the db -->
<?php


// Verifica se o método é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recupera e sanitiza os dados do formulário
    $usuario = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha   = filter_input(INPUT_POST, 'passe', FILTER_SANITIZE_STRING);

    // Conecta à base de dados
    $conn = new mysqli("localhost", "root", "", "fetafacil");
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Prepara a query para buscar o usuário
    $sql = "SELECT passe FROM administrador WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na query: " . $conn->error);
    }
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o usuário foi encontrado
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($senhaArmazenada);
        $stmt->fetch();

        // Para senhas armazenadas em texto puro, compara diretamente
        // Em produção, recomenda-se usar password_hash() e password_verify()
        if ($senha === $senhaArmazenada) {
            // Autenticação bem-sucedida: cria a sessão e redireciona
            $_SESSION['usuario'] = $usuario;
            header("Location: index.php");
            exit();
        } else {
            // Senha incorreta
            $_SESSION['erro'] = "Usuário ou senha incorretos.";
            header("Location: index.php");
            exit();
        }
    } else {
        // Usuário não encontrado
        $_SESSION['erro'] = "Usuário ou senha incorretos.";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
//  else {
//     // Se o método não for POST, redireciona para a página de login
//     header("Location: login.php");
//     exit();
// }


// if (/* isset($_SESSION['REST-admin']) */true) {
session_start();

?>


    <!DOCTYPE html>
    <html lang="PT-pt">

    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>Login</title>

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
                                <!-- <div class="row row-cols-2">


                                </div> -->

                                <form action="index.php" method="post">
                                    <section class="vh-100" style="background-color: #ffffff;">
                                        <div class="container py-5 h-100">
                                            <div class="row d-flex justify-content-center align-items-center h-100">
                                                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                                                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                                                        <div class="card-body p-5 text-center">

                                                            <h3 class="mb-5">Login</h3>

                                                            <div class="form-outline mb-4">
                                                                <input type="email" id="typeEmailX-2" name="email"
                                                                    class="form-control form-control-lg" placeholder="Email" required />
                                                            </div>

                                                            <div class="form-outline mb-4">
                                                                <input type="password" id="typePasswordX-2" name="passe"
                                                                    class="form-control form-control-lg" placeholder="Palavra passe" required />

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

                                <!-- /ROW 1 -->
                    </section>

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

        .btn-lista {
            font-weight: 700;
            color: #fff;
            font-size: 15px;
        }

        .btn-lista:hover {
            font-weight: 700;
            color: #fff;
            font-size: 16px;
        }

        #button {
            font-size: 20px;
        }


        /* .erro-info {} */

        .erro-info span {
            font-size: 3.5rem;
        }

        #nome {
            color: #333;
        }

        .dataClient {
            background-color: rgb(160, 171, 180);
            display: flex;
            justify-content: space-between;
            width: 500vh;
        }

        .form-row {
            justify-content: space-around;
        }

        .modal-body {
            background-color: #ddd;
        }

        .modal-title {
            color: #333;
        }
    </style>

    </html>
<!-- <?php //} else {
//} -->
