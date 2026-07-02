<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = trim($_POST['preco'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $imagemNome = null;

    if (!empty($_FILES['imagem']['name'])) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = 'prod_' . time() . '_' . uniqid() . '.' . strtolower($extensao);
        $destino = __DIR__ . '/assets/uploads/' . $nomeArquivo;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $imagemNome = $nomeArquivo;
        } else {
            $erro = 'Não foi possível enviar a imagem.';
        }
    }

    if ($nome !== '' && $preco !== '' && empty($erro)) {
        $stmt = $pdo->prepare('INSERT INTO categorias (nome) VALUES (?)');
        $stmt->execute([$categoria !== '' ? $categoria : 'Geral']);
        $categoriaId = $pdo->lastInsertId();

        $stmt = $pdo->prepare('INSERT INTO produtos (categoria_id, nome, descricao, preco, imagem, ativo) VALUES (?, ?, ?, ?, ?, 1)');
        if ($stmt->execute([$categoriaId, $nome, $descricao, $preco, $imagemNome])) {
            $mensagem = 'Produto cadastrado com sucesso!';
        } else {
            $erro = 'Não foi possível salvar o produto.';
        }
    } elseif (empty($erro)) {
        $erro = 'Preencha nome e preço do produto.';
    }
}

$categorias = $pdo->query('SELECT id, nome FROM categorias ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$produtos = $pdo->query('SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, c.nome as categoria FROM produtos p LEFT JOIN categorias c ON c.id = p.categoria_id ORDER BY p.id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="page">
    <h1>Produtos</h1>
    <p>Cadastre sabores, tamanhos e valores.</p>

    <?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form-card">
        <div class="grid-2">
            <div>
                <label>Nome</label>
                <input type="text" name="nome" required>
            </div>
            <div>
                <label>Categoria</label>
                <input type="text" name="categoria" placeholder="Ex: Pizza, Bebida">
            </div>
        </div>
        <div class="grid-2">
            <div>
                <label>Preço</label>
                <input type="text" name="preco" required placeholder="Ex: 29.90">
            </div>
            <div>
                <label>Descrição</label>
                <input type="text" name="descricao" placeholder="Opcional">
            </div>
        </div>
        <div>
            <label>Imagem</label>
            <input type="file" name="imagem" accept="image/*">
        </div>
        <button type="submit" class="btn">Salvar produto</button>
    </form>

    <h3>Produtos cadastrados</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Imagem</th><th>Nome</th><th>Categoria</th><th>Descrição</th><th>Preço</th></tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $item): ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['imagem'])): ?>
                                <img src="assets/uploads/<?php echo htmlspecialchars($item['imagem']); ?>" alt="" class="product-thumb">
                            <?php else: ?>
                                <span class="thumb-placeholder">Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['nome']); ?></td>
                        <td><?php echo htmlspecialchars($item['categoria'] ?? 'Geral'); ?></td>
                        <td><?php echo htmlspecialchars($item['descricao'] ?? '-'); ?></td>
                        <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>