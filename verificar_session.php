<?php
// Define o path do cookie para toda a aplicação
session_set_cookie_params(['path' => '/']);
session_start();

// Verifica se o usuário está logado; se não estiver, redireciona para login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
\end{code}