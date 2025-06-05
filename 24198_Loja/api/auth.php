<?php
// Inclui os arquivos necessários para autenticação
require 'db.php'; // Conexão com base de dados
require 'email.php'; // Funções de envio de email

/**
 * Login de um utilizador
 * 
 * @param string $username -> Nome de utilizador ou email
 * @param string $password -> Password do utilizador
 * @return bool -> true se o login foi bem sucedido, false caso contrário
 */

/**
 * Função para fazer login de um utilizador
 * Verifica se o utilizador existe e se a password está correta
 */
function login($userinput, $password)
{
    global $con;  // Usa a conexão global da base de dados

    // Prepara query que aceita username OU email como entrada
    // O utilizador pode fazer login com qualquer um dos dois
    // Só aceita contas ativas (active = 1)
    $sql = $con->prepare("SELECT * FROM Utilizador WHERE (username = ? OR email = ?) AND active = 1");
    $sql->bind_param('ss', $userinput, $userinput);  // Vincula o mesmo valor aos dois parâmetros
    $sql->execute();
    $result = $sql->get_result();

    // Verifica se encontrou algum utilizador
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Obtém os dados do utilizador
        $_SESSION["user"] = $row; // Guarda os dados do utilizador na sessão

        // Verifica se a password fornecida coincide com a hash armazenada
        if (password_verify($password, $row["password"])) {
            return true;  // Login bem-sucedido
        }
    }
    return false;  // Login falhado
}

/** 
 * Registo de um novo utilizador
 * 
 * @param string $email     -> Email do utilizador
 * @param string $username  -> Nome de utilizador
 * @param string $password  -> Password do utilizador
 * @param string $telemovel -> Número de telemóvel
 * @param string $nif       -> Número de Identificação Fiscal
 * @return bool -> true se o registo foi bem sucedido, false caso contrário
 */

/**
 * Função para registar um novo utilizador no sistema
 * Cria conta inativa que precisa ser ativada por email
 */
function registo($email, $username, $password, $telemovel, $nif)
{
    global $con;
    // 1º - Prepara a query de inserção
    // RoleID = 2 significa utilizador normal (não admin)
    $sql = $con->prepare('INSERT INTO Utilizador(email,username,password,telemovel,nif,token,RoleID) VALUES (?,?,?,?,?,?,2)');

    //2º - Gerar o token aletório
    $token = bin2hex(random_bytes(16));
    //3º - Encriptar a password
    $password = password_hash($password, PASSWORD_DEFAULT);
    //4º - Colocar os dados na query e executar a mesma e ver se deu certo
    $sql->bind_param('ssssss', $email, $username, $password, $telemovel, $nif, $token);
    $sql->execute();
    if ($sql->affected_rows > 0) {
        //5º - Enviar o email com o token para ativar a conta
        send_email($email, 'Ativar a conta', "<a href='localhost/24198_Loja/views/ativarconta.php?email=$email&token=$token'> Ative a sua conta</a>");
        return true;
    } else {
        //O registo falhou
        return false;
    }
}

/**
 * Ativar a conta do utilizador
 * 
 * @param string $email -> Email do utilizador
 * @param string $token -> Token de ativação
 * @return bool -> true se a ativação foi bem sucedida, false caso contrário
 */

/**
 * Função para ativar a conta do utilizador
 * Verifica se o email e token correspondem e ativa a conta
 */
function ativarConta($email, $token)
{
    global $con;
    // Atualiza o campo 'active' para 1 se email e token coincidirem
    $sql = $con->prepare("UPDATE Utilizador SET active = 1 WHERE email = ? AND token = ?");
    $sql->bind_param('ss', $email, $token);
    $sql->execute();
    // Retorna true se alguma linha foi afetada (ativação bem-sucedida)
    if ($sql->affected_rows > 0) {
        return true;
    } else {
        return false; // Token inválido ou email não encontrado
    }
}

// Funções não implementadas ( para desenvolvimento futuro)
function logout() {}
// Funções não implementadas ( para desenvolvimento futuro)
function apagarConta() {}

/**
 * Verifica se o utilizador atual é administrador
 * RoleID = 1 significa administrador
 */
function isAdmin()
{
    if ($_SESSION["user"]["RoleID"] == 1) {
        return true;
    } else {
        return false;
    }
}
