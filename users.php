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
    $nivel = trim($_POST['nivel'] ?? 'Atendente');

    if ($nome !== '' && $usuario !== '' && $senha !== '') {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE usuario = ? LIMIT 1');
        $stmt->execute([$usuario]);
        if ($stmt->fetch()) {
            $erro = 'Este usuário já existe.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO usuarios (nome, usuario, senha, nivel, status) VALUES (?, ?, ?, ?, ? )');
            if ($insert->execute([$nome, $usuario, $hash, $nivel, 'Ativo'])) {
                $mensagem = 'Usuário cadastrado com sucesso!';
            } else {
                $erro = 'Não foi possível cadastrar o usuário.';
            }
        }
    } else {
        $erro = 'Preencha nome, usuário e senha.';
    }
}

$usuarios = $pdo->query('SELECT id, nome, usuario, nivel, status FROM usuarios ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="page">
    <h1>Usuários</h1>
    <p>Cadastre e organize os usuários do sistema.</p>

    <?php if ($mensagem): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>

    <form method="POST" class="form-card">
        <div class="grid-2">
            <div>
                <label>Nome</label>
                <input type="text" name="nome" required>
            </div>
            <div>
                <label>Usuário</label>
                <input type="text" name="usuario" required>
            </div>
        </div>
        <div class="grid-2">
            <div>
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>
            <div>
                <label>Nível</label>
                <select name="nivel">
                    <option value="Atendente">Atendente</option>
                    <option value="Caixa">Caixa</option>
                    <option value="Cozinha">Cozinha</option>
                    <option value="Motoboy">Motoboy</option>
                    <option value="Administrador">Administrador</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn">Salvar usuário</button>
    </form>

    <div class="panel-card">
        <h3>Usuários cadastrados</h3>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nome</th><th>Usuário</th><th>Nível</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['nivel']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>