<?php
// Inclui funções de autenticação
require '../api/auth.php';
// Inicia a sessão
session_start();
// Verifica autenticação
if (!isset($_SESSION["user"])) {
    header("Location: views/login.php");
    exit();
}

require '../api/db.php';

// Limpa carrinho do utilizador após compra
$sql = $con->prepare("DELETE FROM Carrinho WHERE userId = ?");
$userId = $_SESSION["user"]["id"];
$sql->bind_param("i", $userId);
$sql->execute();
// Verifica se limpeza foi bem-sucedida
if ($sql->affected_rows > 0) {
    // Carrinho limpo com sucesso
} else {
    // Erro ao limpar o carrinho
    echo "Erro ao limpar o carrinho.";
}
$sql->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="max-width: 400px;">
        <h1 class="mb-4 text-center">Thank you for your order!</h1>
        <form action="/24198_Loja/index.php" class="text-center">
            <button type="submit" class="btn btn-primary">Back to Home</button>
        </form>
    </div>
</body>

</html>