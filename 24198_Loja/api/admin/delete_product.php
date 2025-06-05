<?php
session_start();
require '../db.php';
require '../auth.php';

// VERIFICAÇÃO DE PERMISSÕES primeiro (segurança prioritária)
if (!isAdmin()) {
    echo json_encode(array("status" => "error", "message" => "Acesso negado"));
    exit();
}

// VALIDAÇÃO DE PARÂMETROS:
// Verifica se o ID foi fornecido via GET
if (!isset($_GET['id'])) {
    echo json_encode(array("status" => "error", "message" => "ID do produto não fornecido"));
    exit();
}

// Obtém o ID do produto a eliminar
$id = $_GET['id'];

// OPERAÇÃO DE ELIMINAÇÃO:
// Query simples de DELETE com WHERE para o ID específico
$sql = $con->prepare("DELETE FROM produto WHERE id = ?");
$sql->bind_param("i", $id); // 'i' = integer
$sql->execute();

// Verifica se alguma linha foi afetada (produto eliminado)
if ($sql->affected_rows > 0) {
    echo json_encode(array("status" => "success", "message" => "Produto eliminado com sucesso"));
} else {
    // Pode significar que o ID não existe ou erro na operação
    echo json_encode(array("status" => "error", "message" => "Erro ao eliminar produto"));
}
// Limpeza de recursos
$sql->close();
$con->close();

/* Operação mais simples mas igualmente protegida
Usa GET para receber o ID (comum em operações de eliminação)
A verificação de affected_rows confirma se algo foi realmente eliminado
Importante: não há confirmação adicional - cuidado em produção!  */