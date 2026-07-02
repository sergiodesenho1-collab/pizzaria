CREATE DATABASE IF NOT EXISTS pizzaria
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE pizzaria;

-- ==========================
-- TABELA DE USUÁRIOS
-- ==========================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('Administrador','Caixa','Atendente','Cozinha','Motoboy') NOT NULL,
    status ENUM('Ativo','Inativo') DEFAULT 'Ativo',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================
-- CLIENTES
-- ==========================
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    endereco VARCHAR(255),
    numero VARCHAR(20),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    referencia VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================
-- CATEGORIAS
-- ==========================
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- ==========================
-- PRODUTOS
-- ==========================
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    ativo TINYINT(1) DEFAULT 1,

    FOREIGN KEY (categoria_id)
    REFERENCES categorias(id)
);

-- ==========================
-- PEDIDOS
-- ==========================
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    usuario_id INT,

    tipo ENUM('Balcão','Mesa','Delivery','Retirada'),

    status ENUM(
        'Recebido',
        'Em preparo',
        'Pronto',
        'Saiu para entrega',
        'Entregue',
        'Cancelado'
    ) DEFAULT 'Recebido',

    total DECIMAL(10,2),

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(cliente_id)
    REFERENCES clientes(id),

    FOREIGN KEY(usuario_id)
    REFERENCES usuarios(id)
);

-- ==========================
-- ITENS DO PEDIDO
-- ==========================
CREATE TABLE itens_pedido (

    id INT AUTO_INCREMENT PRIMARY KEY,

    pedido_id INT,

    produto_id INT,

    quantidade INT,

    preco DECIMAL(10,2),

    subtotal DECIMAL(10,2),

    observacao TEXT,

    FOREIGN KEY(pedido_id)
    REFERENCES pedidos(id),

    FOREIGN KEY(produto_id)
    REFERENCES produtos(id)
);