<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteId = (int)($_POST['cliente_id'] ?? 0);
    $tipo = trim($_POST['tipo'] ?? 'Balcão');
    $total = (float)($_POST['total'] ?? 0);
    $itensSelecionados = $_POST['itens'] ?? [];

    if ($clienteId > 0) {
        $stmt = $pdo->prepare('INSERT INTO pedidos (cliente_id, usuario_id, tipo, status, total) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$clienteId, $_SESSION['usuario_id'], $tipo, 'Recebido', $total])) {
            $pedidoId = (int)$pdo->lastInsertId();

            foreach ($itensSelecionados as $itemId => $quantidade) {
                $quantidade = (int)$quantidade;
                if ($quantidade > 0) {
                    $produto = $pdo->prepare('SELECT id, nome, preco FROM produtos WHERE id = ? LIMIT 1');
                    $produto->execute([$itemId]);
                    $produtoInfo = $produto->fetch(PDO::FETCH_ASSOC);
                    if ($produtoInfo) {
                        $subtotal = $produtoInfo['preco'] * $quantidade;
                        $itemStmt = $pdo->prepare('INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco, subtotal) VALUES (?, ?, ?, ?, ?)');
                        $itemStmt->execute([$pedidoId, $produtoInfo['id'], $quantidade, $produtoInfo['preco'], $subtotal]);
                    }
                }
            }

            $cliente = $pdo->prepare('SELECT nome FROM clientes WHERE id = ?');
            $cliente->execute([$clienteId]);
            $clienteNome = $cliente->fetchColumn() ?: 'Cliente';

            $comandaUrl = 'cozinha.php?pedido=' . $pedidoId . '&cliente=' . urlencode($clienteNome) . '&tipo=' . urlencode($tipo) . '&total=' . urlencode($total);
            $mensagem = 'Pedido registrado com sucesso!';
            header('Location: ' . $comandaUrl);
            exit;
        } else {
            $erro = 'Não foi possível registrar o pedido.';
        }
    } else {
        $erro = 'Selecione um cliente.';
    }
}

$clientes = $pdo->query('SELECT id, nome FROM clientes ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$produtos = $pdo->query('SELECT id, nome, preco FROM produtos ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$pedidos = $pdo->query('SELECT p.id, c.nome as cliente, p.tipo, p.status, p.total FROM pedidos p LEFT JOIN clientes c ON c.id = p.cliente_id ORDER BY p.id DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="page">
    <div class="page-head">
        <div>
            <h1>Novo Pedido</h1>
            <p>Registre vendas com rapidez, clareza e um visual profissional.</p>
        </div>
        <div class="page-pill">Atendimento rápido</div>
    </div>

    <?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>

    <form method="POST" class="form-card">
        <div class="order-layout">
            <div class="order-main">
                <div class="grid-2">
                    <div>
                        <label>Cliente</label>
                        <select name="cliente_id" required>
                            <option value="">Selecione</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo (int)$cliente['id']; ?>"><?php echo htmlspecialchars($cliente['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Tipo</label>
                        <select name="tipo">
                            <option value="Balcão">Balcão</option>
                            <option value="Mesa">Mesa</option>
                            <option value="Delivery">Delivery</option>
                            <option value="Retirada">Retirada</option>
                        </select>
                    </div>
                </div>

                <div class="panel-card">
                    <div class="section-heading">
                        <h3>Cardápio</h3>
                        <span class="section-badge">Seleção rápida</span>
                    </div>
                    <div class="product-grid">
                        <?php foreach ($produtos as $produto): ?>
                            <div class="product-tile">
                                <div>
                                    <strong><?php echo htmlspecialchars($produto['nome']); ?></strong>
                                    <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                                </div>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" data-action="minus" data-id="<?php echo (int)$produto['id']; ?>">−</button>
                                    <input type="number" class="qty-input" name="itens[<?php echo (int)$produto['id']; ?>]" min="0" value="0" data-price="<?php echo (float)$produto['preco']; ?>" data-name="<?php echo htmlspecialchars($produto['nome']); ?>">
                                    <button type="button" class="qty-btn" data-action="plus" data-id="<?php echo (int)$produto['id']; ?>">+</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <aside class="order-summary">
                <h3>Resumo do pedido</h3>
                <p class="summary-sub">Acompanhe os itens escolhidos em tempo real.</p>
                <div id="summary-items" class="summary-items">
                    <div class="summary-empty">Nenhum item selecionado.</div>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <strong id="summary-total">R$ 0,00</strong>
                </div>
                <input type="hidden" name="total" id="totalValue" value="0">
                <button type="submit" class="btn btn-block">Registrar pedido</button>
            </aside>
        </div>
    </form>

    <h3>Últimos pedidos</h3>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Cliente</th><th>Tipo</th><th>Status</th><th>Total</th></tr></thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo (int)$pedido['id']; ?></td>
                        <td><?php echo htmlspecialchars($pedido['cliente'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($pedido['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['status']); ?></td>
                        <td>R$ <?php echo number_format($pedido['total'] ?? 0, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<script>
    const qtyInputs = document.querySelectorAll('.qty-input');
    const summaryItems = document.getElementById('summary-items');
    const summaryTotal = document.getElementById('summary-total');
    const totalValue = document.getElementById('totalValue');

    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function updateSummary() {
        let total = 0;
        let html = '';
        let hasItems = false;

        qtyInputs.forEach((input) => {
            const quantity = parseInt(input.value || '0', 10);
            if (quantity > 0) {
                hasItems = true;
                const price = parseFloat(input.dataset.price || '0');
                const name = input.dataset.name || 'Item';
                total += quantity * price;
                html += `<div class="summary-item"><span>${name} × ${quantity}</span><strong>${formatCurrency(quantity * price)}</strong></div>`;
            }
        });

        if (!hasItems) {
            summaryItems.innerHTML = '<div class="summary-empty">Nenhum item selecionado.</div>';
        } else {
            summaryItems.innerHTML = html;
        }

        summaryTotal.textContent = formatCurrency(total);
        totalValue.value = total.toFixed(2);
    }

    qtyInputs.forEach((input) => {
        input.addEventListener('input', updateSummary);
    });

    document.querySelectorAll('.qty-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const input = document.querySelector(`.qty-input[name="itens[${id}]"]`);
            if (!input) return;
            const action = button.getAttribute('data-action');
            const current = parseInt(input.value || '0', 10);
            input.value = action === 'plus' ? current + 1 : Math.max(0, current - 1);
            updateSummary();
        });
    });

    updateSummary();
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>