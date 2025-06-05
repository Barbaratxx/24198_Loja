<?php

// Inclui autenticação e inicia sessão
require '../api/auth.php';
session_start();

// Verificação de autenticação padrão
if (!isset($_SESSION["user"])) {
    header("Location: views/login.php");
    exit();
}

require '../api/db.php';

// Verifica se recebeu o ID do produto a remover
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["produtoId"])) {

    // Obtém dados do utilizador atual
    $userId = $_SESSION["user"]["id"];
    $produtoId = $con->real_escape_string($_POST["produtoId"]);

    // Remove o produto específico do carrinho do utilizador
    $sql = $con->prepare("DELETE FROM Carrinho WHERE userId = ? AND produtoId = ?");
    $sql->bind_param("ii", $userId, $produtoId);

    // Executa e verifica resultado
    if ($sql->execute()) {
        header("Location: ../views/cart.php"); // Redireciona para carrinho
        exit();
    } else {
        echo "Erro ao remover produto do carrinho.";
    }
}
