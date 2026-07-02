<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$totais = $pdo->query('SELECT COALESCE(SUM(total),0) as total_vendas, COUNT(*) as pedidos FROM pedidos WHERE status = "Entregue"')->fetch(PDO::FETCH_ASSOC);
$pendentes = $pdo->query('SELECT COUNT(*) as qtd FROM pedidos WHERE status != "Entregue" AND status != "Cancelado"')->fetch(PDO::FETCH_ASSOC);
?>
<section class="page">
    <h1>Caixa</h1>
    <p>Resumo financeiro e controle rápido do dia.</p>

    <div class="cards">
        <div class="card">
            <h3>Vendas entregues</h3>
            <p class="value">R$ <?php echo number_format($totais['total_vendas'] ?? 0, 2, ',', '.'); ?></p>
        </div>
        <div class="card">
            <h3>Pedidos pendentes</h3>
            <p class="value"><?php echo (int)($pendentes['qtd'] ?? 0); ?></p>
        </div>
        <div class="card">
            <h3>Total de pedidos</h3>
            <p class="value"><?php echo (int)($totais['pedidos'] ?? 0); ?></p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>