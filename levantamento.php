<!-- inicialize the db -->
<?php

//O session deve estar acima de qualquer código, sendo assim a primeira linha para verificar a sessão do usuário
session_start();
if(!isset($_SESSION['feta-admin'])){
    // Caso não tenha sessão iniciada
    // leva direto na pagina inicial.
    header('Location: index.php');
    }
// Dados de conexão
$host     = 'localhost';
$dbname   = 'fetafacil';
$usuario  = 'root';
$senha    = '';
$dsn      = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    // Cria a conexão PDO
    $pdo = new PDO($dsn, $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para buscar os clientes (pode incluir ORDER BY, WHERE, etc.)
    $sql = "SELECT identificador, nome, genero, morada, bi FROM cliente ORDER BY nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Consulta para buscar os tipos de transação
    $sqlTransacao = "SELECT pid, descricao FROM transacao ORDER BY descricao";
    $stmtTransacao = $pdo->prepare($sqlTransacao);
    $stmtTransacao->execute();
    $transacoes = $stmtTransacao->fetchAll(PDO::FETCH_ASSOC);
    //  } catch (PDOException $e) {
    //      echo "Erro ao conectar ou buscar transações: " . $e->getMessage();
    //      exit;
    //  }

    // Busca os registos como array associativo
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}
if (/* isset($_SESSION['REST-admin']) */true) {



?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>Levantamento</title>

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
                            <!-- ROW 1 -->
                            <form id="levantamentoForm" method="post" action="processa_levantamento.php">
                                <div class="container">
                                    <div class="row row-cols-2">
                                        <div class="col-8">
                                            <label>Pesquisa</label>
                                            <div class="card">
                                                <div class="card-body" style="height:300px;">
                                                    <div class="container">
                                                        <div class="row lista-clientes">
                                                            <div class="col-12">
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <!-- <span class="input-group-text" id="basic-addon1"><i
                                                                                class="fas fa-search"></i></span> -->
                                                                        <input type="hidden" id="cliente_identificador" name="cliente_identificador" value="">
                                                                        <!-- Exibição do cliente selecionado (opcional) -->
                                                                        <div id="clienteSelecionado" style="margin-bottom:20px; font-weight:bold;">
                                                                            Nenhum cliente selecionado
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">


                                                                        <!-- Área de pesquisa (resultado) -->
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <!-- <th>ID</th> -->
                                                                                        <th>Nome</th>
                                                                                        <th>Género</th>
                                                                                        <th>BI</th>
                                                                                        <th>Morada</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php foreach ($clientes as $cliente): ?>
                                                                                        <tr class="cliente" required
                                                                                            data-id="<?php echo htmlspecialchars($cliente['identificador']); ?>"
                                                                                            data-nome="<?php echo htmlspecialchars($cliente['nome']); ?>"
                                                                                            data-genero="<?php echo htmlspecialchars($cliente['genero']); ?>"
                                                                                            data-documento="<?php echo htmlspecialchars($cliente['bi']); ?>"
                                                                                            data-telefone="<?php echo htmlspecialchars($cliente['morada']); ?>">

                                                                                            <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                                                                            <td><?php echo htmlspecialchars($cliente['genero']); ?></td>
                                                                                            <td><?php echo htmlspecialchars($cliente['bi']); ?></td>
                                                                                            <td><?php echo htmlspecialchars($cliente['morada']); ?></td>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>

                                                                        <!-- Campo para o preenchimento manual do Agente -->
                                                                        <div class="form-group" style="margin-top:20px;">
                                                                            <label for="agente">Agente:</label>
                                                                            <input type="text" id="agente" name="agente" class="form-control" placeholder="Digite o nome do agente" required>
                                                                        </div>

                                                                        <!-- Campo para selecionar o tipo de transação -->
                                                                        <div class="form-group" style="margin-top:20px;">
                                                                            <label for="transacao_pid">Tipo de Transação:</label>
                                                                            <select name="transacao_pid" id="transacao_pid" class="form-control" required>
                                                                                <option value="">Selecione a transação</option>
                                                                                <?php foreach ($transacoes as $transacao): ?>
                                                                                    <option value="<?php echo htmlspecialchars($transacao['pid']); ?>">
                                                                                        <?php echo htmlspecialchars($transacao['descricao']); ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label>Contas</label>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- /ROW 1 -->
                                <!-- ROW 2 -->
                                <div class="container">
                                    <div class="row row-cols-2">
                                        <div class="col-4 notas-container" style="display:flex; flex-wrap:wrap; gap:10px;">
                                            <label>Quantidade de notas/moedas</label>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group row"> <label for="nota10"
                                                            class="col-sm-4 col-form-label">10</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota10" placeholder="0" min="0"> </div>
                                                    </div>
                                                    <div class="form-group row"> <label for="nota20"
                                                            class="col-sm-4 col-form-label">20</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota20" placeholder="0" min="0"> </div>
                                                    </div>
                                                    <div class="form-group row"> <label for="nota50"
                                                            class="col-sm-4 col-form-label">50</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota50" placeholder="0" min="0"> </div>
                                                    </div>
                                                    <div class="form-group row"> <label for="nota100"
                                                            class="col-sm-4 col-form-label">100</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota100" placeholder="0" min="0"> </div>
                                                    </div>
                                                    <div class="form-group row"> <label for="nota200"
                                                            class="col-sm-4 col-form-label">200</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota200" placeholder="0" min="0"> </div>
                                                    </div>
                                                    <div class="form-group row"> <label for="nota500"
                                                            class="col-sm-4 col-form-label">500</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota500" placeholder="0"> </div>
                                                    </div>
                                                    <div class="form-group row"> <label for="nota1000"
                                                            class="col-sm-4 col-form-label">1000</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota1000" placeholder="0" min="0"> </div>
                                                    </div>
                                                    <div class="form-group row"> <label for="nota2000"
                                                            class="col-sm-4 col-form-label">2000</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota2000" placeholder="0" min="0"> </div>
                                                    </div>
                                                    <div class="form-group row"> <label for="nota5000"
                                                            class="col-sm-4 col-form-label">5000</label>
                                                        <div class="col-sm-8"> <input type="number" class="form-control"
                                                                id="nota5000" placeholder="0" min="0"> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <!-- ROW 2.1 -->
                                            <div class="container">
                                                <div class="row row-cols-2">
                                                    <div class="col-12">
                                                        <label>Total a levantar</label>
                                                        <div class="card">
                                                            <div class="card-body" style="height:150px;">
                                                                <input type="number"
                                                                    style="width:100%;height: 100%;text-align:center;font-size:3.5rem"
                                                                    class="form-control" name="total" id="total" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>Total de notas/moedas</label>
                                                        <div class="card">

                                                            <div class="card-body" style="height:150px;">
                                                                <input type="number"
                                                                    style="width:100%;height: 100%;text-align:center;font-size:3.5rem"
                                                                    class="form-control" id="totalNotas" readonly>
                                                            </div>
                                                            <!-- Botão para confirmar levantamento -->
                                                            <button id="btnConfirmar" class="btn btn-success btn-lg float-right" data-toggle="modal" style="margin-top:20px; display:none;" onclick="mostrarConfirmacao()">Confirmar Levantamento</button>

                                                            <!-- Área para exibir mensagem de erro, se necessário -->
                                                            <div id="erroInfo" style="color:red; margin-top:10px; display:none;">
                                                                <span>ERRO</span> <br>
                                                                <p class="erro-info">TOTAL DAS NOTAS É MAIOR QUE
                                                                    O MONTANTE A LEVANTAR</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <!-- /ROW 2.1 -->
                                                </div>
                                            </div>

                                        </div>



                                        <!-- CONTAINER CONFIRMAR (Oculto inicialmente) -->
                                        <div id="containerConfirmar" class="container" style="display:none; margin-top:20px;">
                                            <div class="row row-cols-1">
                                                <div class="col-12">
                                                    <!-- CONFIRMAR -->
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h2 class="text-center">
                                                                Confirme o valor do levantamento <br>
                                                                e a correspondência das notas
                                                            </h2>
                                                            <br>
                                                            <h1 id="valorLevantamento" class="text-success text-center" style="font-size:3.5rem;"></h1>
                                                            <ul id="listaNotas" class="list-group list-group-flush"></ul>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-light btn-lg"> <a href="levantamento.php">Voltar</a></button>
                                                    <button class="btn btn-primary btn-lg float-right" data-toggle="modal" data-target="#confirmar"> Fazer Levantamento</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- FIM DO CONTAINER CONFIRMAR -->
                                    </div>
                                </div>
                            </form>
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

        /* .erro-info {} */

        .erro-info span {
            font-size: 3.5rem;
        }

        .cliente {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 5px;
        }

        .cliente.selecionado {
            background-color: #e0f7fa;
            border-color: #26c6da;
        }

        .lista-clientes {
            max-height: 235px;
            overflow-y: auto;
        }

        /* Contêiner responsivo para a tabela */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f5f5f5;
        }
    </style>

    </html>
    <script>
        // Ao clicar num cliente, preenche os dados no formulário
        document.querySelectorAll('.cliente').forEach(function(element) {
            element.addEventListener('click', function() {
                var clientId = this.getAttribute('data-id');
                var clientNome = this.getAttribute('data-nome');

                // Define o campo oculto com o id do cliente
                document.getElementById('cliente_identificador').value = clientId;

                // Atualiza a exibição do cliente selecionado
                document.getElementById('clienteSelecionado').innerText = 'Cliente Selecionado: ' + clientNome;

                // Opcional: destacar visualmente o cliente selecionado
                document.querySelectorAll('.cliente').forEach(function(item) {
                    item.classList.remove('selecionado');
                });
                this.classList.add('selecionado');
            });
        });

        // Verifica se um cliente foi selecionado ao submeter o formulário
        document.getElementById('levantamentoForm').addEventListener('submit', function(event) {
            var clientId = document.getElementById('cliente_identificador').value;
            if (clientId === "") {
                alert("Por favor, selecione um cliente na tabela antes de submeter o formulário.");
                event.preventDefault(); // Impede o envio do formulário
            }
        });

        // Função que calcula o total das notas
        function calcularTotalNotas() {
            var notas = {
                "10": parseInt(document.getElementById('nota10').value) || 0,
                "20": parseInt(document.getElementById('nota20').value) || 0,
                "50": parseInt(document.getElementById('nota50').value) || 0,
                "100": parseInt(document.getElementById('nota100').value) || 0,
                "200": parseInt(document.getElementById('nota200').value) || 0,
                "500": parseInt(document.getElementById('nota500').value) || 0,
                "1000": parseInt(document.getElementById('nota1000').value) || 0,
                "2000": parseInt(document.getElementById('nota2000').value) || 0,
                "5000": parseInt(document.getElementById('nota5000').value) || 0
            };

            var totalNotas = 0;
            for (var valor in notas) {
                totalNotas += notas[valor] * parseInt(valor);
            }

            document.getElementById('totalNotas').value = totalNotas;

            var valorTotal = parseFloat(document.getElementById('total').value) || 0;
            var erroInfo = document.getElementById('erroInfo');
            var btnConfirmar = document.getElementById('btnConfirmar');

            if (valorTotal > 0 && totalNotas > valorTotal) {
                erroInfo.style.display = 'block';
                btnConfirmar.style.display = 'none';
            } else {
                erroInfo.style.display = 'none';
                if (totalNotas > 0 && valorTotal > 0) {
                    btnConfirmar.style.display = 'block';
                } else {
                    btnConfirmar.style.display = 'none';
                }
            }
        }

        function mostrarConfirmacao() {
            var totalNotas = document.getElementById('totalNotas').value;
            var valorTotal = document.getElementById('total').value;
            var listaNotas = document.getElementById('listaNotas');

            // Atualiza o valor do levantamento no container de confirmação
            document.getElementById('valorLevantamento').innerText = totalNotas + " AKZ";

            // Limpa a lista antes de adicionar novos itens
            listaNotas.innerHTML = '';

            var notas = {
                "10": parseInt(document.getElementById('nota10').value) || 0,
                "20": parseInt(document.getElementById('nota20').value) || 0,
                "50": parseInt(document.getElementById('nota50').value) || 0,
                "100": parseInt(document.getElementById('nota100').value) || 0,
                "200": parseInt(document.getElementById('nota200').value) || 0,
                "500": parseInt(document.getElementById('nota500').value) || 0,
                "1000": parseInt(document.getElementById('nota1000').value) || 0,
                "2000": parseInt(document.getElementById('nota2000').value) || 0,
                "5000": parseInt(document.getElementById('nota5000').value) || 0
            };

            for (var valor in notas) {
                if (notas[valor] > 0) {
                    var li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.innerText = notas[valor] + " nota(s) de " + valor + " AKZ";
                    listaNotas.appendChild(li);
                }
            }

            document.getElementById('containerConfirmar').style.display = 'block';
        }

        document.getElementById('total').addEventListener('input', calcularTotalNotas);
        ['nota10', 'nota20', 'nota50', 'nota100', 'nota200', 'nota500', 'nota1000', 'nota2000', 'nota5000'].forEach(id => {
            document.getElementById(id).addEventListener('input', calcularTotalNotas);
        });
    </script>



<?php } else {
}
