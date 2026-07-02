# Pizzaria Premium

Sistema web completo para gestão de pizzaria com painel administrativo, controle de pedidos, clientes, produtos, estoque e motoboys.

## Funcionalidades

- Login e autenticação para equipe
- Dashboard com resumo geral
- Cadastro e gestão de clientes
- Cadastro e gestão de produtos
- Controle de pedidos e status
- Gestão de estoque
- Cadastro de motoboys
- Interface responsiva e moderna

## Tecnologias

- PHP
- MySQL / MariaDB
- HTML, CSS e JavaScript
- PDO para conexão com banco

## Requisitos

- XAMPP ou servidor com PHP e MySQL
- Apache e MySQL ativos

## Como usar

1. Clone o repositório
2. Coloque a pasta na pasta htdocs do XAMPP
3. Inicie o Apache e o MySQL
4. Acesse o projeto no navegador:
   - http://localhost/pizzaria/login.php
5. Faça login com:
   - Usuário: admin
   - Senha: 123456

## Estrutura principal

- login.php: tela de autenticação
- dashboard.php: painel principal
- novo_pedido.php: registro de pedidos
- clientes.php: cadastro de clientes
- produtos.php: cadastro de produtos
- estoque.php: controle de estoque
- motoboys.php: cadastro de motoboys
- includes/: arquivos compartilhados de conexão, autenticação e layout

## Observação

O sistema cria automaticamente as tabelas e o usuário administrador na primeira execução, caso o banco ainda não exista.
