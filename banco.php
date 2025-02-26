<?php


    $db_servidor = 'localhost';
    $db_usario = 'root';
    $db_senha = '';
    $db_banco = 'fetafacil';
    
    
    // $conexao = new mysqli($db_servidor, $db_usario, $db_senha, $db_banco);
    
    // if ($conexao->connect_error){
    //     die("Erro na conexao: " . $conexao->connect_error);
    // }

    
    
    $conn = new PDO("mysql:host=localhost;dbname=fetafacil", "root", "");
    
    

    
?>