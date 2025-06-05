<?php
// Inclui funções de autenticação
require "../api/auth.php";
// Verifica se os parâmetros necessários foram enviados via GET
if (isset($_GET["email"]) && isset($_GET["token"])) {
    // Chama função para ativar conta com email e token
    ativarConta($_GET["email"], $_GET["token"]);
    // Redireciona para página de login após ativação
    header("Location: login.php");
    exit(); // Para execução
}
