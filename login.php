<?php
session_start();
require_once __DIR__ . '/includes/conexao.php';

$erro = '';
$loginUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . dirname($_SERVER['PHP_SELF']) . '/login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($usuario !== '' && $senha !== '') {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
        $stmt->execute([':usuario' => $usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $senhaValida = false;
        if ($user) {
            $senhaValida = password_verify($senha, $user['senha']) || md5($senha) === $user['senha'];
        }

        if ($senhaValida) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['nivel'] = $user['nivel'];

            header('Location: dashboard.php');
            exit;
        }

        $erro = 'Usuário ou senha inválidos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pizzaria</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-shell">
        <div class="login-box">
            <div class="brand">
                <img src="assets/imagens/pizza%201.jpeg" alt="Ícone da pizzaria" class="brand-logo">
            </div>
            <h2>Acesse o painel</h2>
            <p>Controle pedidos, clientes e entregas com praticidade.</p>
            <div class="badge">Acesso exclusivo da equipe</div>

            <?php if ($erro): ?>
                <div class="erro"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="POST">
                <label for="usuario">Usuário</label>
                <input id="usuario" type="text" name="usuario" placeholder="Digite seu usuário" required>

                <label for="senha">Senha</label>
                <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>

                <button type="submit">Entrar</button>
            </form>

            <div class="qr-card">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=<?= urlencode($loginUrl) ?>" alt="QR code para acessar o login">
                <div class="qr-content">
                    <strong>Acesse pelo celular</strong>
                    <p>Escaneie o código para abrir a tela de login rapidamente.</p>
                    <a href="<?= htmlspecialchars($loginUrl) ?>" target="_blank" rel="noopener">Abrir no navegador</a>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/login.js"></script>
</body>
</html>