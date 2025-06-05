🛒 Sistema E-commerce PHP
- Um sistema completo de e-commerce desenvolvido em PHP com MySQL, Bootstrap e integração PayPal para pagamentos online.

📋 Índice
- Sobre o Projeto
- Funcionalidades
- Tecnologias Utilizadas
- Pré-requisitos
- Instalação
- Configuração
- Estrutura do Projeto
- Como Usar
- API Endpoints
- Licença
- Contato

🎯 Sobre o Projeto
- Este é um sistema de e-commerce completo desenvolvido como projeto académico, oferecendo uma solução robusta para vendas online com painel administrativo, carrinho de compras e integração de pagamentos.

🎨 Características Principais
- Interface moderna e responsiva
- Sistema de autenticação seguro
- Painel administrativo completo
- Carrinho de compras intuitivo
- Integração com PayPal
- Upload e gestão de imagens
- Validações client-side e server-side

⚡ Funcionalidades - 

👥 Para Utilizadores -
✅ Registo e login de conta
✅ Ativação de conta por email
✅ Navegação de produtos
✅ Carrinho de compras
✅ Atualização de quantidades
✅ Checkout com PayPal
✅ Histórico de compras

🔧 Para Administradores -
✅ Painel de administração
✅ Gestão completa de produtos (CRUD)
✅ Upload de imagens
✅ Visualização de vendas
✅ Gestão de utilizadores

🛠 Tecnologias Utilizadas -

Backend -
- PHP 7.4+ - Linguagem principal
- MySQL - Base de dados
- MySQLi - Interface de base de dados

Frontend -
- HTML5 - Estrutura
- CSS3 - Estilização
- Bootstrap 5.3 - Framework CSS
- JavaScript (ES6+) - Interatividade
- Bootstrap Icons - Ícones

Integração
- PayPal SDK - Processamento de pagamentos
 -AJAX/Fetch API - Comunicação assíncrona

✅ Pré-requisitos -

Antes de começar, certifique-se de ter instalado:
bash- PHP >= 7.4
- MySQL >= 5.7 ou MariaDB >= 10.2
- Servidor web (Apache/Nginx)
- Composer (opcional)
  
🔧 Ambiente de Desenvolvimento Recomendado -
 - XAMPP ou WAMP (Windows)
- LAMP (Linux)
- MAMP (macOS)

📦 Instalação - 

1. Clone o repositório -
bashgit clone https://github.com/seuusuario/sistema-ecommerce-php.git
cd sistema-ecommerce-php

3. Configure o servidor web -
Coloque os arquivos na pasta do seu servidor web:
- XAMPP: C:\xampp\htdocs\24198_Loja
- WAMP: C:\wamp64\www\24198_Loja
- Linux: /var/www/html/24198_Loja

4. Configure a base de dados --
sql-- Crie uma nova base de dados
CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Importe o arquivo SQL (se disponível)
-- mysql -u root -p ecommerce_db < database/schema.sql

5. Estrutura da Base de Dados --
sql-- Tabela de utilizadores
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    telemovel VARCHAR(15),
    nif VARCHAR(9),
    is_admin BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT FALSE,
    activation_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de produtos
CREATE TABLE produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    imagem LONGBLOB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela do carrinho
CREATE TABLE Carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    produtoId INT NOT NULL,
    quantidade INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produtoId) REFERENCES produto(id) ON DELETE CASCADE
);

⚙️ Configuração -

1. Configuração da Base de Dados
Edite o arquivo api/db.php:
php<?php
$servername = "localhost";
$username = "root";        // Seu utilizador MySQL
$password = "";            // Sua senha MySQL
$dbname = "ecommerce_db";  // Nome da base de dados
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Conexão falhou: " . $con->connect_error);
}
$con->set_charset("utf8mb4");
?>

2. Configuração do PayPal -
No arquivo cart.php, atualize com suas credenciais:
php// Substitua pelo seu Client ID do PayPal
$PAYPAL_CLIENT_ID = "SEU_PAYPAL_CLIENT_ID_AQUI";

4. Configuração de Email (se aplicável)
Configure as definições de SMTP no arquivo api/auth.php para ativação de contas.

📁 Estrutura do Projeto
24198_Loja/
├── 📁 api/
│   ├── 📁 admin/
│   │   ├── delete_product.php
│   │   ├── edit_product.php
│   │   └── insert_product.php
│   ├── auth.php
│   ├── db.php
│   ├── update_cart.php
│   └── delete_cart.php
├── 📁 views/
│   ├── areaadmin.php
│   ├── ativarconta.php
│   ├── cart.php
│   ├── finish.php
│   ├── login.php
│   ├── logout.php
│   └── registo.php
├── 📁 assets/
│   ├── 📁 css/
│   ├── 📁 js/
│   └── 📁 images/
├── index.php
└── README.md

🚀 Como Usar --

1. Acesso Inicial -
Navegue para http://localhost/24198_Loja
Registe uma nova conta
Ative a conta (se configurado email)
Faça login

2. Para Administradores -
Configure um utilizador como admin na base de dados:
sqlUPDATE users SET is_admin = TRUE WHERE username = 'admin';
Acesse http://localhost/24198_Loja/views/areaadmin.php

3. Gestão de Produtos -
Adicionar: Clique em "Inserir Novo Produto"
Editar: Clique no ícone de lápis
Eliminar: Clique no ícone do lixo

4. Processo de Compra -
Adicione produtos ao carrinho
Vá para o carrinho
Ajuste quantidades se necessário
Proceda ao checkout com PayPal

🔗 API Endpoints --
Autenticação -
MétodoEndpointDescriçãoPOST/api/auth.phpLogin/RegistoGET/views/ativarconta.phpAtivação de conta
Produtos (Admin) -
MétodoEndpointDescriçãoPOST/api/admin/insert_product.phpCriar produtoPOST/api/admin/edit_product.phpEditar produtoGET/api/admin/delete_product.phpEliminar produto
Carrinho -
MétodoEndpointDescriçãoPOST/api/update_cart.phpAtualizar carrinhoPOST/api/delete_cart.phpRemover do carrinho

🛡️ Segurança --
Este projeto implementa várias medidas de segurança:
✅ Prepared Statements - Prevenção de SQL Injection
✅ Password Hashing - Senhas criptografadas
✅ XSS Protection - htmlspecialchars()
✅ Session Management - Controlo de sessões
✅ Input Validation - Validação de dados
✅ File Upload Security - Validação de imagens

📄 Licença --
Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.
MIT License
Copyright (c) 2025 Bárbara Teixeira
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

📞 Contato --
Bárbara Teixeira  barbaratxx@gmail.com
https://github.com/Barbaratxx/24198_Loja

🙏 Agradecimentos --
Bootstrap pela framework CSS
PayPal pela API de pagamentos
Bootstrap Icons pelos ícones
Comunidade PHP pelas melhores práticas

