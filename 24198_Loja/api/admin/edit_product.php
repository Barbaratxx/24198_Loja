<?php
session_start();
require '../db.php';
require '../auth.php';

// Define que a resposta será em JSON (importante para APIs)
header('Content-Type: application/json');

// VALIDAÇÃO COMPLETA: Verifica todos os campos obrigatórios
if (!isset($_POST['id']) || !isset($_POST['nome']) || !isset($_POST['descricao']) || !isset($_POST['preco'])) {
    echo json_encode(array("status" => "error", "message" => "Faltam dados obrigatórios"));
    exit();
}

// Verifica permissões de administrador
if (!isAdmin()) {
    echo json_encode(array("status" => "error", "message" => "Acesso negado"));
    exit();
}

// Sanitiza e prepara os dados
$id = intval($_POST['id']);  // Conversão para inteiro (segurança)
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$preco = $_POST['preco'];

// LÓGICA CONDICIONAL PARA IMAGEM:
// Verifica se uma nova imagem foi enviada
if (isset($_FILES['imagem']) && $_FILES['imagem']['size'] > 0) {

    // CENÁRIO 1: Nova imagem fornecida - atualiza tudo incluindo imagem
    $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
    $sql = $con->prepare("UPDATE produto SET nome=?, descricao=?, preco=?, imagem=? WHERE id=?");
    $sql->bind_param("ssdsi", $nome, $descricao, $preco, $imagem, $id);
    $sql->send_long_data(3, $imagem); // Envia dados binários da imagem
} else {

    // CENÁRIO 2: Sem nova imagem - atualiza apenas texto e preço
    $sql = $con->prepare("UPDATE produto SET nome=?, descricao=?, preco=? WHERE id=?");
    $sql->bind_param("ssdi", $nome, $descricao, $preco, $id);
}

$sql->execute();

// Verifica resultado da operação
if ($sql->affected_rows > 0) {
    echo json_encode(array("status" => "success", "message" => "Produto atualizado com sucesso"));
} else {

    // Pode significar que não houve alterações ou erro

    echo json_encode(array("status" => "error", "message" => "Nenhuma alteração feita ou erro ao atualizar produto"));
}

$sql->close();
$con->close();

/* Função inteligente que permite editar com ou sem nova imagem
A lógica condicional evita substituir a imagem desnecessariamente
Usa queries diferentes dependendo se há nova imagem ou não
O header JSON é importante para comunicação com frontend */