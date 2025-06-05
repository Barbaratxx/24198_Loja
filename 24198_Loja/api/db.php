<?php
// Ativa o relatório de erros do MySQLi para facilitar o debug
// Quando há um erro na query, será exibida uma mensagem detalhada

mysqli_report(MYSQLI_REPORT_ERROR);

// Cria uma nova conexão MySQLi com a base de dados
// Parâmetros: servidor, utilizador, password, nome da base de dados    
$con = new mysqli("localhost", "root", "", "24198_Loja");
// Verifica se houve erro na conexão
if ($con->connect_error) {
  die("connection failed: " . $con->connect_error);
}

/* Este arquivo é responsável por estabelecer a conexão com a base de dados MySQL
A variável $con será utilizada em todos os outros arquivos para executar queries
O mysqli_report() é importante para desenvolvimento, pois mostra erros detalhados */