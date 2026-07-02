<?php
require_once __DIR__ . '/config.php';

$dsn = 'mysql:host=' . DB_HOST . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec('CREATE DATABASE IF NOT EXISTS ' . DB_NAME . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $pdo->exec('USE ' . DB_NAME);

    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        nivel ENUM('Administrador','Caixa','Atendente','Cozinha','Motoboy') NOT NULL,
        status ENUM('Ativo','Inativo') DEFAULT 'Ativo',
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        telefone VARCHAR(20),
        endereco VARCHAR(255),
        numero VARCHAR(20),
        bairro VARCHAR(100),
        cidade VARCHAR(100),
        referencia VARCHAR(255),
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        categoria_id INT,
        nome VARCHAR(150) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        imagem VARCHAR(255) DEFAULT NULL,
        ativo TINYINT(1) DEFAULT 1,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS estoque (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(150) NOT NULL,
        quantidade INT DEFAULT 0,
        tipo VARCHAR(100) DEFAULT 'Ingrediente',
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS configuracoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        chave VARCHAR(100) NOT NULL UNIQUE,
        valor TEXT NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cliente_id INT,
        usuario_id INT,
        tipo ENUM('Balcão','Mesa','Delivery','Retirada'),
        status ENUM('Recebido','Em preparo','Pronto','Saiu para entrega','Entregue','Cancelado') DEFAULT 'Recebido',
        total DECIMAL(10,2),
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(cliente_id) REFERENCES clientes(id),
        FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS itens_pedido (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pedido_id INT,
        produto_id INT,
        quantidade INT,
        preco DECIMAL(10,2),
        subtotal DECIMAL(10,2),
        observacao TEXT,
        FOREIGN KEY(pedido_id) REFERENCES pedidos(id),
        FOREIGN KEY(produto_id) REFERENCES produtos(id)
    )");

    $pdo->exec("ALTER TABLE produtos ADD COLUMN IF NOT EXISTS imagem VARCHAR(255) DEFAULT NULL");

    $stmt = $pdo->prepare('SELECT id, usuario FROM usuarios WHERE usuario = :usuario LIMIT 1');
    $stmt->execute([':usuario' => 'admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $senhaHash = password_hash('123456', PASSWORD_DEFAULT);
        $update = $pdo->prepare('UPDATE usuarios SET nome = ?, senha = ? WHERE id = ?');
        $update->execute(['Administrador', $senhaHash, $admin['id']]);
    } else {
        $senhaHash = password_hash('123456', PASSWORD_DEFAULT);
        $insert = $pdo->prepare('INSERT INTO usuarios (nome, usuario, senha, nivel, status) VALUES (?, ?, ?, ?, ?)');
        $insert->execute(['Administrador', 'admin', $senhaHash, 'Administrador', 'Ativo']);
    }

    $defaults = [
        ['site_name', 'Pizzaria Premium'],
        ['site_slogan', 'Sabor, rapidez e organização para sua equipe'],
        ['site_logo', 'assets/imagens/pizza 1.jpeg']
    ];

    foreach ($defaults as $item) {
        $check = $pdo->prepare('SELECT id FROM configuracoes WHERE chave = ? LIMIT 1');
        $check->execute([$item[0]]);
        if (!$check->fetch()) {
            $insertCfg = $pdo->prepare('INSERT INTO configuracoes (chave, valor) VALUES (?, ?)');
            $insertCfg->execute([$item[0], $item[1]]);
        }
    }
} catch (PDOException $e) {
    die('Erro na conexão: ' . $e->getMessage());
}