<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
    $clienteId = (int)($_POST['cliente_id'] ?? 0);
    if ($clienteId > 0) {
        try {
            $pdo->beginTransaction();

            $deleteItens = $pdo->prepare('DELETE FROM itens_pedido WHERE pedido_id IN (SELECT id FROM pedidos WHERE cliente_id = ?)');
            $deleteItens->execute([$clienteId]);

            $deletePedidos = $pdo->prepare('DELETE FROM pedidos WHERE cliente_id = ?');
            $deletePedidos->execute([$clienteId]);

            $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = ?');
            if ($stmt->execute([$clienteId])) {
                $pdo->commit();
                $mensagem = 'Cliente excluído com sucesso!';
            } else {
                $pdo->rollBack();
                $erro = 'Não foi possível excluir o cliente.';
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = 'Não foi possível excluir o cliente por causa de dependências do sistema.';
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');

    if ($nome !== '') {
        $stmt = $pdo->prepare('INSERT INTO clientes (nome, telefone, endereco, bairro) VALUES (?, ?, ?, ?)');
        if ($stmt->execute([$nome, $telefone, $endereco, $bairro])) {
            $mensagem = 'Cliente cadastrado com sucesso!';
        } else {
            $erro = 'Não foi possível salvar o cliente.';
        }
    } else {
        $erro = 'Informe o nome do cliente.';
    }
}

$clientes = $pdo->query('SELECT id, nome, telefone, endereco, bairro FROM clientes ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="page">
    <h1>Clientes</h1>
    <p>Cadastro e histórico de clientes.</p>

    <?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>

    <form method="POST" class="form-card">
        <div class="grid-2">
            <div>
                <label>Nome</label>
                <input type="text" name="nome" required>
            </div>
            <div>
                <label>Telefone</label>
                <input type="text" name="telefone" placeholder="(11) 99999-9999">
            </div>
        </div>
        <div class="grid-2">
            <div>
                <label>Endereço</label>
                <input type="text" name="endereco" placeholder="Rua, número">
            </div>
            <div>
                <label>Bairro</label>
                <input type="text" name="bairro" placeholder="Bairro">
            </div>
        </div>
        <button type="submit" class="btn">Salvar cliente</button>
    </form>

    <h3>Clientes cadastrados</h3>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nome</th><th>Telefone</th><th>Endereço</th><th>Bairro</th></tr></thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['telefone'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($cliente['endereco'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($cliente['bairro'] ?? '-'); ?></td>
                        <td>
                            <form method="POST" class="inline-form" onsubmit="return confirm('Deseja realmente excluir este cliente?');">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="cliente_id" value="<?php echo (int)$cliente['id']; ?>">
                                <button type="submit" class="btn small">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>