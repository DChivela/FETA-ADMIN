<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = array();

// Se desejar remover o cookie de sessão também
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroi a sessão
session_destroy();

// Redireciona para a página de login (ou outra página desejada)
header("Location: index.php");
exit();
?>
