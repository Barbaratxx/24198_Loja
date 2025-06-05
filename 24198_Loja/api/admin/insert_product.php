<?php

// Inicia sessão e inclui dependências necessárias
session_start();
require '../db.php';  // Conexão com base de dados
require '../auth.php'; // Funções de autenticação


// VALIDAÇÃO 1: Verifica se todos os campos obrigatórios foram enviados
if (!isset($_POST['nome']) || !isset($_POST['descricao']) || !isset($_POST['preco'])) {

    // Retorna erro em formato JSON (importante para APIs)
    echo json_encode(array("status" => "error", "message" => "Faltam dados obrigatórios"));
    exit(); // Para a execução
}


// VALIDAÇÃO 2: Verifica se o utilizador é administrador
if (!isAdmin()) {
    echo json_encode(array("status" => "error", "message" => "Acesso negado"));
    exit();
}


// PROCESSAMENTO DA IMAGEM:
// file_get_contents() lê o arquivo temporário e converte para binário
// $_FILES['imagem']['tmp_name'] é o caminho temporário do arquivo enviado
$imagem = file_get_contents($_FILES['imagem']['tmp_name']);

// Prepara query de inserção
// Nota: 'b' no bind_param indica que o parâmetro é binário (BLOB)
$sql = $con->prepare("INSERT INTO produto (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)");
$sql->bind_param("ssdb", $_POST['nome'], $_POST['descricao'], $_POST['preco'], $imagem);

// IMPORTANTE: send_long_data() é usado para dados binários grandes (imagens)
// Parâmetro 3 refere-se à 4ª posição (índice 3) na query (campo imagem)
$sql->send_long_data(3, $imagem);
$sql->execute();

// Verifica se a inserção foi bem-sucedida
if ($sql->affected_rows > 0) {
    echo json_encode(array("status" => "success", "message" => "Produto inserido com sucesso"));
} else {
    echo json_encode(array("status" => "error", "message" => "Erro ao inserir produto"));
}

// Boa prática: fechar conexões explicitamente
$sql->close();
$con->close();

/* Este endpoint é exclusivo para administradores criarem novos produtos
Aceita dados via POST incluindo upload de imagem
A imagem é armazenada como BLOB na base de dados
Retorna respostas em JSON para facilitar integração com frontend */