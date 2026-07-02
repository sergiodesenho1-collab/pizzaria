<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$pedidoId = (int)($_GET['pedido'] ?? 0);
$clienteNome = trim($_GET['cliente'] ?? '');
$tipo = trim($_GET['tipo'] ?? 'Balcão');
$total = (float)($_GET['total'] ?? 0);
?>
<section class="page">
    <h1>Cozinha</h1>
    <p>Comanda pronta para preparo e impressão.</p>

    <?php if ($pedidoId > 0): ?>
        <div class="comanda-card">
            <h2>Comanda #<?php echo $pedidoId; ?></h2>
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($clienteNome ?: 'Cliente'); ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($tipo); ?></p>
            <p><strong>Total:</strong> R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
            <p><strong>Status:</strong> Recebido</p>
            <hr>
            <p>Pedido enviado para a cozinha.</p>
            <button onclick="window.print()" class="btn print-btn">Imprimir comanda</button>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Nenhuma comanda aberta no momento.</div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>