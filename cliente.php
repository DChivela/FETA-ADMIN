<!-- inicialize the db -->
<?php
// Verifica se o termo foi enviado
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Conexão à base de dados
    $conn = new mysqli("localhost", "root", "", "fetafacil");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Query para obter o total de clientes
    $sqlTotal = "SELECT COUNT(*) AS total FROM cliente";
    $stmt = $conn->prepare($sqlTotal);
    $stmt->execute();
    $stmt->bind_result($totalClientes); //para armazenar o total
    $stmt->fetch();
    $stmt->close();

    // Busca os detalhes do cliente pelo ID
    $sql = "SELECT * FROM cliente WHERE identificador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    // Armazena os resultados
    $cliente = $resultado->fetch_assoc();

    // Fecha a conexão
    $stmt->close();
    $conn->close();
} else {
    //Redireciona para a página caso nenhum ID seja encontrado.
    header("Location: index.php");
    exit;
}

session_start();
if (/* isset($_SESSION['REST-admin']) */true) {
?>


    <!DOCTYPE html>
    <html lang="PT-pt">

    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>Cliente</title>

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
                                <div class="row row-cols-2">
                                    <div class="col-8">
                                        <div class="container card">

                                            <div class="card-body" style="overflow-y: auto; max-height: 250px; position: sticky; top: 0;">
                                                <!-- Botão para invocar o modal -->
                                                <!-- <a href="#" class="btn btn-info" data-toggle="modal" data-target="#editClientModal">Editar</a> -->

                                                <?php if (!empty($cliente)): ?>
                                                    <!-- Detalhes do Cliente -->
                                                    <h3 id="nome" style="position: sticky; top: 0; background: white; height: 50px;">
                                                        <?= htmlspecialchars($cliente['nome']) ?><hr>
                                                    </h3>
                                                    
                                                    <p> <strong> NIF</strong> <?= htmlspecialchars($cliente['nif']) ?></p>
                                                    <p> <strong> Morada:</strong> <?= htmlspecialchars($cliente['morada']) ?></p>
                                                    <p> <strong> Género:</strong> <?= htmlspecialchars($cliente['genero']) ?></p>
                                                    <p> <strong> BI:</strong> <?= htmlspecialchars($cliente['bi']) ?></p>
                                                    <p> <strong> Data Nascimento:</strong> <?= htmlspecialchars($cliente['nascimento']) ?></p>
                                                    <p> <strong> Estado Civil:</strong> <?= htmlspecialchars($cliente['estado_civil']) ?></p>
                                                    <p> <strong> Província:</strong> <?= htmlspecialchars($cliente['provincia']) ?></p>
                                                    <p> <strong> Município:</strong> <?= htmlspecialchars($cliente['natural_de']) ?></p>
                                                    <p> <strong> Ocupação:</strong> <?= htmlspecialchars($cliente['ocupacao']) ?></p>
                                                    <p> <strong> ALtura:</strong> <?= htmlspecialchars($cliente['altura']) ?></p>
                                                    <p> <strong> Filiação</strong> <?= htmlspecialchars($cliente['filiacao']) ?></p>
                                                    <p> <strong> Balanço</strong> <?= htmlspecialchars($cliente['balanco']) ?></p>
                                                    <!--Caso for necessário acrescer campos, que esta é a área -->

                                                <?php else: ?>
                                                    <p>Cliente não encontrado.</p>
                                                <?php endif; ?>
                                                <!-- <button class="btn btn-primary" id="button"><a href="clientes.php" class="btn-lista">Ver a lista</a></button> -->
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label></label>
                                            <label></label>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="info-display">
                                                        <div class="info-item" style="font-weight:bold;"> <span
                                                                class="info-icon">&#9432;</span>
                                                            <span>Saldo atual:</span> <span
                                                                style="float:right;margin-top: 12px;">500.000</span>
                                                        </div>
                                                        <div class="info-item"> <span class="info-icon">&#9432;</span>
                                                            <span>Conta:</span> <span
                                                                style="float:right;margin-top: 12px;">Particular</span>
                                                        </div>
                                                        <div class="info-item"> <span class="info-icon">&#9432;</span>
                                                            <span>Desde:</span> <span
                                                                style="float:right;margin-top: 12px;">23H76M766 17843</span>
                                                        </div>
                                                    </div>
                                                </div>
                                        <!-- <a class="btn btn-lg btn-primary form-control" href="createCliente.php">Cadastrar Clientes</a> -->
                                    </div>
                                </div>
                            </div>

                            <!-- /ROW 1 -->
                    </section>
                    <section class="container">
                        <div class="row">
                            <div class="col-3">

                                <div class="row mb-4">
                                    <div class="col-md-6"> <select class="form-control" id="year">
                                            <option value="2024">2024</option>
                                            <option value="2023">2023</option>
                                            <!-- Adicione mais anos conforme necessário -->
                                        </select> </div>
                                    <div class="col-md-6"> <select class="form-control" id="month">
                                            <option value="Janeiro">Janeiro</option>
                                            <option value="Fevereiro">Fevereiro</option>
                                            <!-- Adicione mais meses conforme necessário -->
                                        </select> </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <ul id="tabs" class="nav nav-tabs">
                                    <li class="nav-item"><a href="" data-target="#todas-transacoes" data-toggle="tab"
                                            class="nav-link small text-uppercase active">Todas</a></li>
                                    <li class="nav-item"><a href="" data-target="#entrada-transacoes" data-toggle="tab"
                                            class="nav-link small text-uppercase">Entrada</a></li>
                                    <li class="nav-item"><a href="" data-target="#saida-transacoes" data-toggle="tab"
                                            class="nav-link small text-uppercase">Saida</a></li>
                                </ul>
                                <br>
                                <div id="tabsContent" class="tab-content" style="overflow-y:scroll;max-height:500px">
                                    <div id="todas-transacoes" class="tab-pane fade active show">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th></th>
                                                        <th>Descrição</th>
                                                        <th>ID Transação</th>
                                                        <th>Tipo</th>
                                                        <th>Cliente</th>
                                                        <th>Data</th>
                                                        <th>Valor</th>
                                                        <th>Comprovativo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><i class="fas fa-arrow-down text-danger"></i></td>
                                                        <td> Levantamento </td>
                                                        <td>#12548796</td>
                                                        <td> Saída</td>
                                                        <td>999 999 999</td>
                                                        <td>28 Jan, 12.30 AM</td>
                                                        <td class="text-danger">- 2500</td>
                                                        <td><a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-download"></i> Baixar</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fas fa-arrow-up text-success"></i></td>
                                                        <td>Depósito</td>
                                                        <td>#12548796</td>
                                                        <td> Entrada</td>
                                                        <td>999 999 999</td>
                                                        <td>25 Jan, 10.40 PM</td>
                                                        <td class="text-success">+ 750</td>
                                                        <td><a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-download"></i> Baixar</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fas fa-arrow-down text-danger"></i>
                                                        </td>
                                                        <td> Levantamento
                                                        </td>
                                                        <td>#12548796</td>
                                                        <td> Saída</td>
                                                        <td>999 999 999</td>
                                                        <td>20 Jan, 10.40 PM</td>
                                                        <td class="text-danger">- 150</td>
                                                        <td><a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-download"></i> Baixar</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fas fa-arrow-down text-danger"></i>
                                                        </td>
                                                        <td>Levantamento</td>
                                                        <td>#12548796</td>
                                                        <td> Saída</td>
                                                        <td>999 999 999</td>
                                                        <td>15 Jan, 03.29 PM</td>
                                                        <td class="text-danger">- 1050</td>
                                                        <td><a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-download"></i> Baixar</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fas fa-arrow-up text-success"></i>
                                                        </td>
                                                        <td>Depósito</td>
                                                        <td>#12548796</td>
                                                        <td> Entrada</td>
                                                        <td>999 999 999</td>
                                                        <td>14 Jan, 10.40 PM</td>
                                                        <td class="text-success">+ 840</td>
                                                        <td><a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-download"></i> Baixar</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="entrada-transacoes" class="tab-pane fade">

                                    </div>
                                    <div id="saida-transacoes" class="tab-pane fade">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="container">
                        <div class="row">
                            <div class="col-3">
                                <button class="btn btn-lg btn-primary form-control">Baixar Extrato</button>
                                <button class="btn btn-lg btn-danger form-control">Enviar Extrato ao Cliente</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-lg btn-primary form-control">Receber Numerário</button>
                                <button class="btn btn-lg btn-danger form-control">Bloquear agente</button>
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
    <!-- Modal -->
    <div class="modal fade " id="editClientModal" tabindex="-1" role="dialog" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog col-4" role="document">
            <div class="modal-content dataClient">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientModalLabel">Atualizar dados do cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" value="<?= htmlspecialchars($cliente['nome']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="BI">BI</label>
                                <input type="text" class="form-control" id="BI" value="<?= htmlspecialchars($cliente['bi']) ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="Genero">Género</label>
                                <input type="text" class="form-control" id="genero" value="<?= htmlspecialchars($cliente['genero']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="clientPhone">Telefone</label>
                                <input type="text" class="form-control" id="clientPhone">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="clientDOB">Data de nascimento</label>
                                <input type="date" class="form-control" id="clientDOB" value="<?= htmlspecialchars($cliente['nascimento']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="clientProvince">Província</label>
                                <input type="text" class="form-control" id="provincia" value="<?= htmlspecialchars($cliente['provincia']) ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="clientMunicipality">Município</label>
                                <input type="text" class="form-control" id="municipio" value="<?= htmlspecialchars($cliente['natural_de']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="clientDistrict">Bairro / Distrito</label>
                                <input type="text" class="form-control" id="morada" value="<?= htmlspecialchars($cliente['morada']) ?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Atualizar dados do cliente</button>
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
<?php } else {
}
