<aside class="sidebar">
    <div class="logo-brand">
        <img src="assets/imagens/pizza 1.jpeg" alt="Logo da pizzaria" class="brand-avatar">
        <div>
            <div class="brand-title"><?= htmlspecialchars($siteName ?? 'Pizzaria Premium') ?></div>
            <div class="brand-subtitle"><?= htmlspecialchars($siteSlogan ?? 'Sabor e organização') ?></div>
        </div>
    </div>
    <div class="user-chip">Olá, <?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="novo_pedido.php">Novo pedido</a>
        <a href="pedidos.php">Pedidos</a>
        <a href="produtos.php">Produtos</a>
        <a href="clientes.php">Clientes</a>
        <a href="caixa.php">Caixa</a>
        <a href="cozinha.php">Cozinha</a>
        <a href="estoque.php">Estoque</a>
        <a href="motoboys.php">Motoboys</a>
        <a href="relatorios.php">Relatórios</a>
        <a href="users.php">Usuários</a>
        <a href="configuracoes.php">Configurações</a>
        <a href="logout.php">Sair</a>
    </nav>
</aside>