<?php

// Verifica se o utilizador está autenticado

require 'auth.php';
// Inicia a sessão para verificar se o utilizador está logado
session_start();
// Verificação de segurança: só utilizadores autenticados podem adicionar ao carrinho
if (!isset($_SESSION["user"])) {
    header("Location: ../views/login.php"); // Redireciona para login
    exit();
}

// Recebe o post com o produto_id e quantidade
// Inclui a conexão com a base de dados
require 'db.php';

// Verifica se a requisição é POST e contém os dados necessários
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['produto_id']) && isset($_POST['quantidade'])) {
    // Verificar se o produto já está no carrinho e se sim, atualizar a quantidade 
    // Converte os valores para inteiros para segurança
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);

    // Verifica se o produto já existe no carrinho do utilizador
    $sql = $con->prepare("SELECT quantidade FROM Carrinho WHERE produtoId = ? AND userId = ?");
    $sql->bind_param("ii", $produto_id, $_SESSION['user']['id']);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {

        // CENÁRIO 1: Produto já existe no carrinho
        // Obtém a quantidade atual
        // Produto já existe no carrinho, atualizar a quantidade
        $row = $result->fetch_assoc();
        $nova_quantidade = $row['quantidade'] + $quantidade;

        // Atualiza a quantidade existente
        $update_sql = $con->prepare("UPDATE Carrinho SET quantidade = ? WHERE produtoId = ? AND userId = ?");
        $update_sql->bind_param("iii", $nova_quantidade, $produto_id, $_SESSION['user']['id']);
        $update_sql->execute();
    } else {

        // CENÁRIO 2: Produto não existe no carrinho
        // Adiciona novo item ao carrinho
        // Produto não existe no carrinho, adicionar novo item
        $insert_sql = $con->prepare("INSERT INTO Carrinho (produtoId, userId, quantidade) VALUES (?, ?, ?)");
        $insert_sql->bind_param("iii", $produto_id, $_SESSION['user']['id'], $quantidade);
        $insert_sql->execute();
    }
    // Após adicionar/atualizar, redireciona para a página principal
    // Se não, adicionar o produto ao carrinho
    header("Location: ../index.php");
}
