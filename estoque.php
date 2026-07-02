<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $quantidade = (int)($_POST['quantidade'] ?? 0);
    $tipo = trim($_POST['tipo'] ?? 'Ingrediente');

    if ($nome !== '') {
        $stmt = $pdo->prepare('INSERT INTO estoque (nome, quantidade, tipo) VALUES (?, ?, ?)');
        if ($stmt->execute([$nome, $quantidade, $tipo])) {
            $mensagem = 'Item cadastrado no estoque.';
        } else {
            $erro = 'Não foi possível salvar o item.';
        }
    } else {
        $erro = 'Informe o nome do item.';
    }
}

$itens = $pdo->query('SELECT id, nome, quantidade, tipo FROM estoque ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="page">
    <h1>Estoque</h1>
    <p>Gerencie ingredientes e itens do cardápio.</p>

    <?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>

    <form method="POST" class="form-card">
        <div class="grid-2">
            <div>
                <label>Nome do item</label>
                <input type="text" name="nome" required>
            </div>
            <div>
                <label>Quantidade</label>
                <input type="number" name="quantidade" value="0" min="0">
            </div>
        </div>
        <div>
            <label>Tipo</label>
            <select name="tipo">
                <option value="Ingrediente">Ingrediente</option>
                <option value="Embalagem">Embalagem</option>
                <option value="Bebida">Bebida</option>
            </select>
        </div>
        <button type="submit" class="btn">Salvar item</button>
    </form>

    <div class="panel-card">
        <h3>Itens em estoque</h3>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Item</th><th>Quantidade</th><th>Tipo</th></tr></thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nome']); ?></td>
                            <td><?php echo (int)$item['quantidade']; ?></td>
                            <td><?php echo htmlspecialchars($item['tipo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>