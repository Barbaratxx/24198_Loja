<?php
// Inclui autenticação e inicia sessão
require '../api/auth.php';
session_start();


// Verifica autenticação (mesmo padrão dos outros arquivos)
if (!isset($_SESSION["user"])) {
    header("Location: views/login.php");
    exit();
}

require '../api/db.php';

// Verifica se recebeu os dados necessários via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["produtoId"]) && isset($_POST["quantidade"])) {

    // Obtém os dados do utilizador e sanitiza os inputs
    $userId = $_SESSION["user"]["id"];
    $produtoId = $con->real_escape_string($_POST["produtoId"]);
    $quantidade = $con->real_escape_string($_POST["quantidade"]);

    // LÓGICA INTELIGENTE: Se quantidade for 0 ou negativa, remove o item
    if ($quantidade <= 0) {

        // Remove o produto do carrinho
        $sql = $con->prepare("DELETE FROM Carrinho WHERE userId = ? AND produtoId = ?");
        $sql->bind_param("ii", $userId, $produtoId);
    } else {

        // Atualiza a quantidade do produto
        $sql = $con->prepare("UPDATE Carrinho SET quantidade = ? WHERE userId = ? AND produtoId = ?");
        $sql->bind_param("iii", $quantidade, $userId, $produtoId);
    }
    // Executa a operação e verifica o resultado
    if ($sql->execute()) {
        header("Location: ../views/cart.php"); // Volta para a página do carrinho
        exit();
    } else {
        echo "Erro ao atualizar carrinho."; // Mensagem de erro simples
    }
}
