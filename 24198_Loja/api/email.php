<?php

// Importa as classes necessárias do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/**
 * Função para enviar emails usando PHPMailer
 * Utilizada principalmente para ativação de contas
 */

function send_email($to, $subject, $message)
{
    // Inclui os arquivos necessários do PHPMailer
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require "secrets.php"; // Carrega credenciais do email

    //Enviar o email de activação
    $mail = new PHPMailer(true);

    try {
        // Configuração de caracteres para suporte a acentos
        $mail->CharSet = "utf-8";
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.sapo.pt';                         //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $EMAIL_SAPO;                            //SMTP username
        $mail->Password   = $EMAIL_PASS;                            //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //Recipients
        // Configuração do remetente e destinatário
        $mail->setFrom($EMAIL_SAPO, "App Loja 24198");
        $mail->addAddress($to, $to);     //Add a recipient

        //Content
        // Configuração do conteúdo do email
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Se houver erro no envio, retorna false
        // Em produção, seria interessante registar o erro num log
        return false;
    }
}
