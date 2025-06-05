<!-- Página com CSS/Bootstrap com um formulário para o login -->

<?php
// Inclui funções de autenticação
session_start();
// Verifica se o utilizador já está logado
require "../api/auth.php";
// Se o utilizador já estiver logado, redireciona para a página inicial
$error_msg = false;
$msg = "";

// Verifica se formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];
    // Validação de campos vazios
    if (empty($username) || empty($password)) {
        $error_msg = true;
        $msg = "Preencha todos os campos";
    } else {
        // Tenta fazer o login
        if (login($username, $password)) {
            header("Location: ../index.php"); // Redireciona para a página inicial após login bem-sucedido
        } else {
            $error_msg = true;
            $msg = "O login falhou. Verifique o seu username e password.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CORES: */
        .bg-primary,
        .navbar.bg-primary,
        .card-header.bg-primary {
            background-color: #81688a !important;
        }

        .navbar .navbar-brand,
        .navbar .navbar-nav .nav-link,
        .bg-primary,
        .text-bg-primary {
            color: #fff !important;
        }

        .btn-primary {
            background-color: #89a28b !important;
            border-color: #89a28b !important;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #81688a !important;
            border-color: #81688a !important;
        }
    </style>
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <?php
    if ($error_msg) {
        echo "<div class='position-fixed top-0 end-0 p-3' style='z-index: 1050;'>
                  <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                      $msg
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>
              </div>";
    }
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">Login</h1>

                        <!-- Formulário de Login -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <input type="submit" value="Login" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (optional, for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>