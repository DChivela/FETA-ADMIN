<!-- inicialize the db -->
<?php

$conn = new mysqli("localhost", "root", "", "fetafacil");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// -----------------------------------------------------
// 1) Consultar DEPÓSITOS
// -----------------------------------------------------
$sqlDepositos = "
SELECT 
DAYOFWEEK(STR_TO_DATE(CONCAT(dia, '-', mes, '-', ano), '%d-%m-%Y')) AS diaIndex,
SUM(total) AS somaDepositos
FROM deposito
WHERE STR_TO_DATE(CONCAT(dia, '-', mes, '-', ano), '%d-%m-%Y') >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DAYOFWEEK(STR_TO_DATE(CONCAT(dia, '-', mes, '-', ano), '%d-%m-%Y'))
ORDER BY DAYOFWEEK(STR_TO_DATE(CONCAT(dia, '-', mes, '-', ano), '%d-%m-%Y'))
";
$resultDep = $conn->query($sqlDepositos);

$depositosPorDia = []; // [1 => 100, 2 => 200, ...]
if ($resultDep) {
    while ($row = $resultDep->fetch_assoc()) {
        $diaIndex = (int)$row['diaIndex'];
        $depositosPorDia[$diaIndex] = (float)$row['somaDepositos'];
    }
}

// -----------------------------------------------------
// 2) Consultar LEVANTAMENTOS
// -----------------------------------------------------
$sqlLevantamentos = "
SELECT 
DAYOFWEEK(STR_TO_DATE(CONCAT(dia, '-', mes, '-', ano), '%d-%m-%Y')) AS diaIndex,
SUM(total) AS somaLevantamentos
FROM levantamento
WHERE STR_TO_DATE(CONCAT(dia, '-', mes, '-', ano), '%d-%m-%Y') >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DAYOFWEEK(STR_TO_DATE(CONCAT(dia, '-', mes, '-', ano), '%d-%m-%Y'))
ORDER BY DAYOFWEEK(STR_TO_DATE(CONCAT(dia, '-', mes, '-', ano), '%d-%m-%Y'))
";
$resultLev = $conn->query($sqlLevantamentos);

$levantamentosPorDia = [];
if ($resultLev) {
    while ($row = $resultLev->fetch_assoc()) {
        $diaIndex = (int)$row['diaIndex'];
        $levantamentosPorDia[$diaIndex] = (float)$row['somaLevantamentos'];
    }
}



session_start();
if (/* isset($_SESSION['REST-admin']) */true) {

    // Verifica se há uma mensagem de sucesso na sessão e exibe
    if (isset($_SESSION['mensagem_sucesso'])) {
        echo '
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); width: 80%; max-width: 600px; font-size: 1.2rem; z-index: 9999;">
        <strong>Sucesso!</strong> ' . $_SESSION['mensagem_sucesso'] . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
        <script>
        // Remove a mensagem após 5 segundos
        setTimeout(function() {
            $(".alert").alert("close");
        }, 5000);
    </script>
    ';
        // Limpa a mensagem de sucesso após exibi-la
        unset($_SESSION['mensagem_sucesso']);
    }

?>
    <!DOCTYPE html>
    <html lang="en">

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
                        <div class="section-body ">
                            <br>
                            <!-- ROW 1 -->
                            <div class="container">
                                <div class="row row-cols-2">
                                    <div class="col-8">
                                        <label>Pesquisa</label>
                                        <div class="card">
                                            <div class="card-body" style="height:260px; overflow-y: auto;">

                                                <?php
                                                $dados = []; // Variável para armazenar os resultados da pesquisa
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

                                                    if (!$resultado) {
                                                        echo "<p style='color: red;'>Erro ao executar a pesquisa. Por favor, tente novamente mais tarde.</p>";
                                                    } else {
                                                        // Armazena os resultados
                                                        while ($row = $resultado->fetch_assoc()) {
                                                            $dados[] = $row;
                                                        }
                                                    }

                                                    // Fecha a conexão
                                                    $stmt->close();
                                                    $conn->close();
                                                }
                                                ?>
                                                <?php if (!empty($dados)): ?>
                                                    <ul style="list-style-type: none; padding: 0;">
                                                        <?php foreach ($dados as $dado): ?>
                                                            <li>
                                                                <a href="cliente.php?id=
                                                                <?= htmlspecialchars($dado['identificador']) ?>"
                                                                    class="btn btn-info" style="margin-left: 10px;">Ver</a><br>
                                                                <strong> <?= htmlspecialchars($dado['nome']) ?></strong><br>
                                                                <?= htmlspecialchars($dado['genero']) ?> <br>
                                                                <?= htmlspecialchars($dado['bi']) ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php elseif (isset($_GET['termo'])): ?>
                                                    <p style="margin-top: 10px; color:#333; font-weight:700;">Nenhum resultado
                                                        encontrado para
                                                        "<strong><?= htmlspecialchars($_GET['termo']) ?></strong>".</p>
                                                <?php else: ?>
                                                    <p style="margin-top: 25px; color: #2c72ce; font-weight:700;">Digite algo
                                                        acima para iniciar a pesquisa...</p>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label>Contas</label>
                                        <div class="card">
                                            <div class="card-body">
                                                <?php
                                                $conn = new mysqli("localhost", "root", "", "fetafacil");
                                                if ($conn->connect_error) {
                                                    die("Erro de conexão: " . $conn->connect_error);
                                                }

                                                // Exemplo de nomes de colunas: 
                                                // Tabela "deposito": id, cliente_id, total
                                                // Tabela "cliente": id, nome, tipo

                                                // 1. Soma total de todos os depósitos (sem filtrar tipo)
                                                $sqlTodas = "SELECT SUM(d.total) AS soma 
                                                            FROM deposito d
                                                            JOIN cliente c ON d.cliente_identificador = c.identificador";
                                                $resultTodas = $conn->query($sqlTodas);
                                                $rowTodas = $resultTodas->fetch_assoc();
                                                $todas = $rowTodas['soma'] ?? 0;

                                                // 2. Soma total de Particulares
                                                $sqlParticulares = "SELECT SUM(d.total) AS soma
                                                                    FROM deposito d
                                                                    JOIN cliente c ON d.cliente_identificador = c.identificador
                                                                    WHERE c.tipo = 'Agente'";
                                                $resultParticulares = $conn->query($sqlParticulares);
                                                $rowParticulares = $resultParticulares->fetch_assoc();
                                                $particulares = $rowParticulares['soma'] ?? 0;

                                                // 3. Soma total de Empresa
                                                $sqlEmpresa = "SELECT SUM(d.total) AS soma
                                                            FROM deposito d
                                                            JOIN cliente c ON d.cliente_identificador = c.identificador
                                                            WHERE c.tipo = 'Empresa'";
                                                $resultEmpresa = $conn->query($sqlEmpresa);
                                                $rowEmpresa = $resultEmpresa->fetch_assoc();
                                                $empresa = $rowEmpresa['soma'] ?? 0;

                                                $conn->close();
                                                ?>

                                                <div class="info-display">
                                                    <div class="info-item">
                                                        <span class="info-icon">&#9432;</span>
                                                        <span>Todas:</span>
                                                        <span style="float:right;margin-top: 12px;">
                                                            <?= number_format($todas, 2, '.', ',') ?>
                                                        </span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-icon">&#9432;</span>
                                                        <span>Particulares:</span>
                                                        <span style="float:right;margin-top: 12px;">
                                                            <?= number_format($particulares, 2, '.', ',') ?>
                                                        </span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-icon">&#9432;</span>
                                                        <span>Empresa:</span>
                                                        <span style="float:right;margin-top: 12px;">
                                                            <?= number_format($empresa, 2, '.', ',') ?>
                                                        </span>
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
                                    <div class="col-8">
                                        <label>Atividade semanal</label>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="myChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label>Caixa</label>
                                        <div class="card">
                                            <div class="card-body">
                                                <form method="GET" action="">
                                                    <div class="container mt-3">
                                                        <div class="row mb-4">
                                                            <div class="col-md-6">
                                                                <select class="form-control" name="year">
                                                                    <option value="2025">2025</option>
                                                                    <option value="2024">2024</option>
                                                                    <option value="2023">2023</option>
                                                                    <!-- etc. -->
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <select class="form-control" name="month">
                                                                    <option value="Janeiro">Janeiro</option>
                                                                    <option value="Fevereiro">Fevereiro</option>
                                                                    <option value="Março">Março</option>
                                                                    <option value="Abril">Abril</option>
                                                                    <option value="Maio">Maio</option>
                                                                    <option value="Junho">Junho</option>
                                                                    <option value="Julho">Julho</option>
                                                                    <option value="Agosto">Agosto</option>
                                                                    <option value="Setembro">Setembro</option>
                                                                    <option value="Outubro">Outubro</option>
                                                                    <option value="Novembro">Novembro</option>
                                                                    <option value="Dezembro">Dezembro</option>
                                                                    <!-- Ajuste para todos os meses -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                                                    </div>
                                                </form>
                                                <?php
                                                if (isset($_GET['year']) && isset($_GET['month'])) {

                                                    // Mapeia o nome do mês para número, se necessário
                                                    // Se na nossa tabela, 'mes' já for '01', '02', etc., e o <option> do HTML
                                                    // for algo como "Fevereiro", precisamos converter.
                                                    // Exemplo do mapeamento (adaptando ao nosso caso):
                                                    $monthMap = [
                                                        'Janeiro'   => '01',
                                                        'Fevereiro' => '02',
                                                        'Março'     => '03',
                                                        'Abril'     => '04',
                                                        'Maio'      => '05',
                                                        'Junho'     => '06',
                                                        'Julho'     => '07',
                                                        'Agosto'    => '08',
                                                        'Setembro'  => '09',
                                                        'Outubro'   => '10',
                                                        'Novembro'  => '11',
                                                        'Dezembro'  => '12',
                                                    ];

                                                    // Resgata o ano e mês selecionados
                                                    $selectedYear = $_GET['year'];
                                                    $selectedMonth = $_GET['month'];

                                                    // Se o usuário escolheu "Janeiro", converte para "01"
                                                    if (isset($monthMap[$selectedMonth])) {
                                                        $mes = $monthMap[$selectedMonth];
                                                    } else {
                                                        // Se o <option> já for o número (ex: "03"), use direto
                                                        $mes = $selectedMonth;
                                                    }

                                                    // Agora vamos buscar os totais no banco
                                                    $conn = new mysqli("localhost", "root", "", "fetafacil");
                                                    if ($conn->connect_error) {
                                                        die("Erro de conexão: " . $conn->connect_error);
                                                    }

                                                    // 1) Soma de depósitos
                                                    $sqlDepositos = "SELECT SUM(total) AS somaDepositos
                                                                 FROM deposito
                                                                 WHERE mes = '$mes' AND ano = '$selectedYear'";
                                                    $resultDep = $conn->query($sqlDepositos);
                                                    $rowDep = $resultDep->fetch_assoc();
                                                    $entrada = $rowDep['somaDepositos'] ?? 0;

                                                    // 2) Soma de levantamentos
                                                    $sqlLevantamentos = "SELECT SUM(total) AS somaLevantamentos
                                                                     FROM levantamento
                                                                     WHERE mes = '$mes' AND ano = '$selectedYear'";
                                                    $resultLev = $conn->query($sqlLevantamentos);
                                                    $rowLev = $resultLev->fetch_assoc();
                                                    $saida = $rowLev['somaLevantamentos'] ?? 0;

                                                    // Exemplo de cálculo para "Central" e "Ativos" (caso queira)
                                                    // Aqui vamos supor que "Central" é (Entrada - Saída)
                                                    // e "Ativos" é só um exemplo de 10% do valor que sobrou. Ajuste como quiser.
                                                    $central = $entrada - $saida;
                                                    $ativos  = $central * 0.10; // Exemplo: 10% de "Central"

                                                    $conn->close();

                                                    // -----------------------------------------------------
                                                    // 3) Montar arrays fixos para a semana
                                                    //    1=Dom,2=Seg,3=Ter,4=Qua,5=Qui,6=Sex,7=Sáb
                                                    // -----------------------------------------------------
                                                    $mapDias = [
                                                        1 => 'Dom',
                                                        2 => 'Seg',
                                                        3 => 'Ter',
                                                        4 => 'Qua',
                                                        5 => 'Qui',
                                                        6 => 'Sex',
                                                        7 => 'Sáb'
                                                    ];

                                                    // Arrays finais para o Chart.js
                                                    $labels = [];
                                                    $arrayDepositos = [];
                                                    $arrayLevantamentos = [];

                                                    // Preenchemos do diaIndex=1 até 7, garantindo que haja valor (mesmo que 0)
                                                    for ($i = 1; $i <= 7; $i++) {
                                                        $labels[] = $mapDias[$i];
                                                        $arrayDepositos[] = $depositosPorDia[$i] ?? 0;
                                                        $arrayLevantamentos[] = $levantamentosPorDia[$i] ?? 0;
                                                    }

                                                    // Converter para JSON
                                                    $jsLabels         = json_encode($labels);
                                                    $jsDepositos      = json_encode($arrayDepositos);
                                                    $jsLevantamentos  = json_encode($arrayLevantamentos);

                                                ?>
                                                    <div class="financial-summary">
                                                        <div class="financial-item">
                                                            <span class="label">Entrada:</span>
                                                            <span class="value text-success">
                                                                <?= number_format($entrada, 2, '.', ',') ?>
                                                            </span>
                                                        </div>
                                                        <div class="financial-item">
                                                            <span class="label">Saida:</span>
                                                            <span class="value text-danger">
                                                                <?= number_format($saida, 2, '.', ',') ?>
                                                            </span>
                                                        </div>
                                                        <hr>
                                                        <div class="financial-item">
                                                            <span class="label">Central:</span>
                                                            <span class="value text-danger">
                                                                <?= number_format($central, 2, '.', ',') ?>
                                                            </span>
                                                        </div>
                                                        <div class="financial-item">
                                                            <span class="label">Ativos:</span>
                                                            <span class="value text-cyan">
                                                                <?= number_format($ativos, 2, '.', ',') ?>
                                                            </span>
                                                        </div>


                                                    <?php
                                                } else {
                                                    // Se não tiver nada selecionado ainda, pode exibir algo fixo ou vazio
                                                    echo "<p class='mt-3'>Selecione o ano e o mês para ver os valores.</p>";
                                                }
                                                    ?>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- /ROW 2 -->
                                <!-- ROW 3 -->
                                <div class="container">
                                    <div class="row row-cols-2">
                                        <div class="col-4">
                                            <label>Agentes</label>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div id="carouselExampleIndicators" class="carousel slide"
                                                        data-ride="carousel">
                                                        <ol class="carousel-indicators">
                                                            <li data-target="#carouselExampleIndicators" data-slide-to="0"
                                                                class="active"></li>
                                                            <li data-target="#carouselExampleIndicators" data-slide-to="1">
                                                            </li>
                                                            <li data-target="#carouselExampleIndicators" data-slide-to="2">
                                                            </li>
                                                        </ol>
                                                        <div class="carousel-inner">
                                                            <div class="carousel-item active">
                                                                <div
                                                                    class="d-flex justify-content-center align-items-center">
                                                                    <div class="icon-box text-center"> <i
                                                                            class="fa fa-home fa-5x" aria-hidden="true"></i>
                                                                        <h5>Item 1</h5>
                                                                        <p>Detalhes do Item 1</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <div
                                                                    class="d-flex justify-content-center align-items-center">
                                                                    <div class="icon-box text-center"> <i
                                                                            class="fa fa-car fa-5x" aria-hidden="true"></i>
                                                                        <h5>Item 2</h5>
                                                                        <p>Detalhes do Item 2</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <div
                                                                    class="d-flex justify-content-center align-items-center">
                                                                    <div class="icon-box text-center"> <i
                                                                            class="fa fa-tree fa-5x" aria-hidden="true"></i>
                                                                        <h5>Item 3</h5>
                                                                        <p>Detalhes do Item 3</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <a class="carousel-control-prev"
                                                            href="#carouselExampleIndicators" role="button"
                                                            data-slide="prev"> <span class="carousel-control-prev-icon"
                                                                aria-hidden="true"></span> <span
                                                                class="sr-only">Previous</span> </a> <a
                                                            class="carousel-control-next" href="#carouselExampleIndicators"
                                                            role="button" data-slide="next"> <span
                                                                class="carousel-control-next-icon"
                                                                aria-hidden="true"></span> <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <label>Ativos</label>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="chart-container"> <canvas id="chart"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- /ROW 3 -->
                                </div>
                    </section>
                </div>
                <!-- footer include here -->
                <?php
                include("footer.php");
                ?>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Pegar arrays do PHP (já em JSON)
                const labels = <?php echo $jsLabels; ?>;
                const depositos = <?php echo $jsDepositos; ?>;
                const levantamentos = <?php echo $jsLevantamentos; ?>;

                const ctx = document.getElementById('myChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Depósitos',
                                data: depositos,
                                backgroundColor: 'green'
                            },
                            {
                                label: 'Levantamentos',
                                data: levantamentos,
                                backgroundColor: 'red'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        </script>

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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Template JS File         
    -->

        <script src="assets/js/scripts.js"></script>
        <script src="assets/js/custom.js"></script>

        <style>
            .financial-summary {
                background-color: #fff;
                padding: 20px 0;
            }

            .financial-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 15px;
                font-size: 1.2rem;
            }

            .label {
                font-weight: bold;
            }

            .text-success {
                color: green;
            }

            .text-danger {
                color: red;
            }

            .text-pink {
                color: #ff1493;
            }

            .text-cyan {
                color: cyan;
            }

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
        </style>

        
    </body>

    </html>

    <script>
            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Sab', 'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex'],
                        datasets: [{
                            label: 'Deposito',
                            data: [200, 100, 250, 300, 150, 200, 250],
                            backgroundColor: 'green'
                        }, {
                            label: 'Levantamento',
                            data: [450, 300, 200, 450, 100, 350, 400],
                            backgroundColor: 'red'
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: 500
                                }
                            }]
                        }
                    }
                });
            });


            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('chart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez', 'Jan'],
                        datasets: [{
                            label: 'Dados',
                            data: [150, 300, 450, 750, 350, 250, 600],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: 800
                                }
                            }]
                        }
                    }
                });
            });


            $(".search-element").show();
        </script>


<?php } else {
}
