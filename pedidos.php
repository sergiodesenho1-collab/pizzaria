<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['status'])) {
    $pedidoId = (int)$_POST['pedido_id'];
    $status = trim($_POST['status']);
    $stmt = $pdo->prepare('UPDATE pedidos SET status = ? WHERE id = ?');
    if ($stmt->execute([$status, $pedidoId])) {
        $mensagem = 'Status atualizado com sucesso!';
    }
}

$pedidos = $pdo->query('SELECT p.id, c.nome as cliente, p.tipo, p.status, p.total, p.criado_em FROM pedidos p LEFT JOIN clientes c ON c.id = p.cliente_id ORDER BY p.id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="page">
    <h1>Pedidos</h1>
    <p>Acompanhe e atualize o status de todos os pedidos.</p>

    <?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Cliente</th><th>Tipo</th><th>Status</th><th>Total</th><th>Data</th><th>Ação</th></tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo (int)$pedido['id']; ?></td>
                        <td><?php echo htmlspecialchars($pedido['cliente'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($pedido['tipo']); ?></td>
                        <td><span class="status-pill status-<?php echo str_replace(' ', '\\ ', htmlspecialchars($pedido['status'])); ?>"><?php echo htmlspecialchars($pedido['status']); ?></span></td>
                        <td>R$ <?php echo number_format($pedido['total'] ?? 0, 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($pedido['criado_em']); ?></td>
                        <td>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="pedido_id" value="<?php echo (int)$pedido['id']; ?>">
                                <select name="status">
                                    <option value="Recebido" <?php echo $pedido['status'] === 'Recebido' ? 'selected' : ''; ?>>Recebido</option>
                                    <option value="Em preparo" <?php echo $pedido['status'] === 'Em preparo' ? 'selected' : ''; ?>>Em preparo</option>
                                    <option value="Pronto" <?php echo $pedido['status'] === 'Pronto' ? 'selected' : ''; ?>>Pronto</option>
                                    <option value="Saiu para entrega" <?php echo $pedido['status'] === 'Saiu para entrega' ? 'selected' : ''; ?>>Saiu para entrega</option>
                                    <option value="Entregue" <?php echo $pedido['status'] === 'Entregue' ? 'selected' : ''; ?>>Entregue</option>
                                    <option value="Cancelado" <?php echo $pedido['status'] === 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                </select>
                                <button type="submit" class="btn small">Atualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>