<?php
// Inclui funções de autenticação
// Inicia a sessão
header("Location: login.php");
session_destroy(); // Destrói sessão (limpa dados do utilizador)
exit(); // Para execução
