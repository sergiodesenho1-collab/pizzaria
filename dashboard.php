<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$totais = $pdo->query('SELECT COUNT(*) AS total_pedidos, COALESCE(SUM(total),0) AS faturamento FROM pedidos')->fetch(PDO::FETCH_ASSOC);
$entregues = $pdo->query('SELECT COUNT(*) AS qtd FROM pedidos WHERE status = "Entregue"')->fetch(PDO::FETCH_ASSOC);
$clientes = $pdo->query('SELECT COUNT(*) AS qtd FROM clientes')->fetch(PDO::FETCH_ASSOC);
$produtos = $pdo->query('SELECT COUNT(*) AS qtd FROM produtos')->fetch(PDO::FETCH_ASSOC);
$ultimos = $pdo->query('SELECT p.id, c.nome AS cliente, p.status, p.total, p.criado_em FROM pedidos p LEFT JOIN clientes c ON c.id = p.cliente_id ORDER BY p.id DESC LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="page">
    <div class="hero-card">
        <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['nome']) ?> 🍕</h1>
        <p>Seu painel está pronto para organizar pedidos, clientes e caixa com rapidez.</p>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Pedidos registrados</h3>
            <p class="value"><?php echo (int)($totais['total_pedidos'] ?? 0); ?></p>
        </div>
        <div class="card">
            <h3>Faturamento</h3>
            <p class="value">R$ <?php echo number_format($totais['faturamento'] ?? 0, 2, ',', '.'); ?></p>
        </div>
        <div class="card">
            <h3>Clientes cadastrados</h3>
            <p class="value"><?php echo (int)($clientes['qtd'] ?? 0); ?></p>
        </div>
        <div class="card">
            <h3>Produtos ativos</h3>
            <p class="value"><?php echo (int)($produtos['qtd'] ?? 0); ?></p>
        </div>
        <div class="card">
            <h3>Pedidos entregues</h3>
            <p class="value"><?php echo (int)($entregues['qtd'] ?? 0); ?></p>
        </div>
    </div>

    <div class="panel-card" style="margin-top:20px;">
        <h3>Últimos pedidos</h3>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Cliente</th><th>Status</th><th>Total</th><th>Data</th></tr></thead>
                <tbody>
                    <?php foreach ($ultimos as $item): ?>
                        <tr>
                            <td><?php echo (int)$item['id']; ?></td>
                            <td><?php echo htmlspecialchars($item['cliente'] ?? '-'); ?></td>
                            <td><span class="status-pill status-<?php echo str_replace(' ', '\\ ', htmlspecialchars($item['status'])); ?>"><?php echo htmlspecialchars($item['status']); ?></span></td>
                            <td>R$ <?php echo number_format($item['total'] ?? 0, 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($item['criado_em']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>