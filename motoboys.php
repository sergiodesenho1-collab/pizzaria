<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/header.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($nome !== '' && $usuario !== '' && $senha !== '') {
        $check = $pdo->prepare('SELECT id FROM usuarios WHERE usuario = ? LIMIT 1');
        $check->execute([$usuario]);
        if ($check->fetch()) {
            $erro = 'Este entregador já está cadastrado.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO usuarios (nome, usuario, senha, nivel, status) VALUES (?, ?, ?, ?, ?)');
            if ($insert->execute([$nome, $usuario, $hash, 'Motoboy', 'Ativo'])) {
                $mensagem = 'Entregador cadastrado com sucesso!';
            } else {
                $erro = 'Não foi possível cadastrar o entregador.';
            }
        }
    } else {
        $erro = 'Preencha nome, usuário e senha.';
    }
}

$motoboys = $pdo->query("SELECT id, nome, usuario, status FROM usuarios WHERE nivel = 'Motoboy' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="page">
    <div class="page-head">
        <div>
            <h1>Motoboys</h1>
            <p>Cadastre e organize os entregadores da sua operação.</p>
        </div>
        <div class="page-pill">Cadastro de entregas</div>
    </div>

    <?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>

    <div class="hero-card">
        <div class="section-heading">
            <h3>Cadastrar motoboy</h3>
            <span class="section-badge">Novo cadastro</span>
        </div>
        <form method="POST" class="form-card">
            <div class="grid-2">
                <div>
                    <label>Nome completo</label>
                    <input type="text" name="nome" placeholder="Ex: João da Silva" required>
                </div>
                <div>
                    <label>Usuário</label>
                    <input type="text" name="usuario" placeholder="Ex: joaosilva" required>
                </div>
            </div>
            <div class="grid-2">
                <div>
                    <label>Senha</label>
                    <input type="password" name="senha" placeholder="Defina uma senha" required>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status" disabled>
                        <option>Ativo</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn">Salvar motoboy</button>
        </form>
    </div>

    <div class="panel-card">
        <div class="section-heading">
            <h3>Motoboys cadastrados</h3>
            <span class="section-badge">Equipe ativa</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Usuário</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($motoboys): ?>
                        <?php foreach ($motoboys as $motoboy): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($motoboy['nome']); ?></td>
                                <td><?php echo htmlspecialchars($motoboy['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($motoboy['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3">Nenhum entregador cadastrado ainda.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>