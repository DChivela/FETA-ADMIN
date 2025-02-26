<!-- inicialize the db -->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION['mintel-admin'])){

    include "banco.php";
    $metadata = $_SESSION['metadata'];

    $query = $conn->prepare("SELECT * FROM `usuario`");
    $query->execute();

    $usuarios = $query->fetchAll();
    

try {

    $consulta = $conn->query("SELECT `identificador`, `nome` FROM `usuario`");

    $dados = array();

    while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
        $dados[] = $row;
    }

    // Converte os dados para JSON
    $dados_json = json_encode($dados);

    // Imprime o valor diretamente no script JavaScript
    echo "<script>var dadosUsuario = $dados_json;</script>";
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}

// Fecha a conexão
$conexao = null;
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
    <?php include("components/butoestop.php"); ?>
    <main class="main">

        <div class="cliente__div" style="height: 150px;">
           
        </div>

        <a href="adicionarusuario.php" style="display:inline-block;margin:40px 0;">
            <img src="image/mais.png" alt="" class="search__img"
            width="50px" height="50px">
        </a>


        <?php include "./components/searchbar.php"?>

        <p class="search__total"><?php echo count($usuarios) ?> CLIENTES
        </p>

        <?php
            // Função de comparação para ordenar os usuários pelo nome
            function compararPorNome($a, $b) {
                return strcmp($a['nome'], $b['nome']);
            }

            // Ordenar o array de usuários usando a função de comparação
            usort($usuarios, 'compararPorNome');

            // Iterar sobre os usuários ordenados
            foreach ($usuarios as $usuario) {
                $v = time();
                $outra = md5($v);
                ?>
                
                <div class="search__box" style="justify-content:space-between;">
                    <p class="search__name" style="margin-left:30px;">
                        <a href="usuario.php?<?php echo $v ?>=<?php echo $outra ?>&ftp=<?php echo $usuario["identificador"] ?>">
                        <?php echo $usuario['nome']?>
                        </a>
                    </p>
                    <p class="search__number" style="margin-right: 30px;"><?php echo $usuario['telefone']?></p>
                </div>

                <?php
            }
            ?>


      

    </main>
   
    
</body>

</html>
<?php }else{ ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
                            <input type="email" id="typeEmailX-2"  name="email"  class="form-control form-control-lg" placeholder="Email" required />
                            </div>

                            <div class="form-outline mb-4">
                            <input type="password" id="typePasswordX-2"  name="passe"  class="form-control form-control-lg" placeholder="Palavra passe" required />
                            
                            </div>
                            <div class="form-outline mb-4">
                                <a href="receber_codigo.php" style="color:red;text-decoration:none;"><p>Esqueci a passe</p></a>
                            </div>


                            <button class="btn btn-primary btn-lg btn-block" type="submit">Entrar</button>

                            
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </section>
            </form>
        </body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
        </html>

<?php }