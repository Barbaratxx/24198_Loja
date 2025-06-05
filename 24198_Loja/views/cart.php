<?php
// Inclui funções de autenticação
require '../api/auth.php';
// Inicia a sessão
session_start();
// Verifica se utilizador está logado

if (!isset($_SESSION["user"])) {
    header("Location: views/login.php");
    exit();
}
// Inclui conexão com o banco de dados
require '../api/db.php';

// Verifica se o carrinho já existe para o utilizador
$sql = $con->prepare("SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, c.quantidade FROM produto p JOIN Carrinho c ON p.id = c.produtoId WHERE c.userId = ?");
$sql->bind_param("i", $_SESSION["user"]["id"]);
$sql->execute();
$result = $sql->get_result();

// Configuração do PayPal (ID do cliente)
$PAYPAL_CLIENT_ID = "AWzFudKtEu4rjPHuyzdpaB-HoJN71FIT9-KzB0Ial2xYZvBZgVfD4c5ORPqzw2k01GIrRkDH0-tFC-Ek";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgba(195, 221, 190, 0.61);
        }

        .cart-item {
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Carrinho de Compras</h2>
        <?php if ($result->num_rows === 0): ?>
            <div class="alert alert-info">O seu carrinho está vazio.</div>
        <?php endif; ?>
        <div class="row">

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-12 cart-item d-flex align-items-center">
                    <?php
                    // Codifica a imagem em base64 para exibição
                    $image = base64_encode($row['imagem']);
                    $src = 'data:image/jpeg;base64,' . $image;
                    ?>
                    <div class="me-4">
                        <img src="<?php echo $src ?>" alt="Imagem">
                    </div>
                    <div class="flex-grow-1">
                        <h5><?php echo htmlspecialchars($row['nome']); ?></h5>
                        <p class="mb-1 text-muted"><?php echo htmlspecialchars($row['descricao']); ?></p>
                        <div class="fw-bold mb-2"><?php echo number_format($row['preco'], 2, ',', '.'); ?> €</div>
                        <div class="cart-actions">
                            <!-- Formulário para atualizar quantidade -->
                            <form action="../api/update_cart.php" method="post" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="produtoId" value="<?php echo $row['id']; ?>">
                                <input type="number" name="quantidade" value="<?php echo $row['quantidade']; ?>" min="1" class="form-control form-control-sm" style="width: 70px;">
                                <button type="submit" class="btn btn-primary btn-sm">Atualizar</button>
                            </form>
                            <!-- Botão para remover item do carrinho -->
                            <form action="../api/delete_cart.php" method="post">
                                <input type="hidden" name="produtoId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                        </div>
                    </div>
                    <!-- Exibe subtotal para cada item -->
                    <div class="ms-auto text-center">
                        <span class="badge bg-secondary fs-6">Subtotal: <?php echo $row["quantidade"] * $row['preco']; ?> €</span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php
        // Reset do ponteiro do resultado para recalcular total
        $result->data_seek(0);
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $total += $row["quantidade"] * $row["preco"];
        }
        ?>
        <?php if ($total > 0): ?>
            <div class="d-flex justify-content-end mt-4">
                <h4>Total do Pedido: <span class="badge bg-success"><?php echo number_format($total, 2, ',', '.'); ?> €</span></h4>
            </div>
        <?php endif; ?>
    </div>
    <div class="d-flex justify-content-center">

        <div id="paypal-button-container" class="w-50"></div>
    </div>
    <!-- JavaScript do PayPal SDK -->
    <script src=<?php echo "https://www.paypal.com/sdk/js?client-id=$PAYPAL_CLIENT_ID&currency=EUR" ?>></script>

    <script>
        paypal.Buttons({
            // Configuração do botão de pagamento
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $total; ?>'
                        }
                    }]
                });
            },
            // Ação ao aprovar o pagamento
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    window.location.href = "finish.php";
                });
            },
            // Tratamento de erros
            onError: function(err) {
                console.error('Erro no pagamento:', err);
                alert('Ocorreu um erro durante o pagamento. Tente novamente.');
            }
        }).render('#paypal-button-container'); // Elemento onde renderizar botões
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>