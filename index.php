<?php
require_once __DIR__ . '/includes/config.php';
?><!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(SITE_NAME) ?></title>
    <style>
        :root { --accent:#b92d3f; --dark:#1d0d10; --light:#fffaf7; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:Segoe UI, sans-serif; background:linear-gradient(135deg,#1d0d10 0%,#4f151d 45%,#b92d3f 100%); color:#fff; }
        .hero { min-height:100vh; display:grid; grid-template-columns:1.1fr 0.9fr; align-items:center; padding:40px; gap:24px; }
        .hero-card { background:rgba(255,255,255,0.12); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.16); border-radius:24px; padding:30px; }
        .hero-card h1 { font-size:2.3rem; margin:0 0 12px; }
        .hero-card p { line-height:1.6; color:rgba(255,255,255,0.9); }
        .hero-card .btn { display:inline-block; margin-top:14px; padding:12px 18px; border-radius:999px; text-decoration:none; color:#fff; font-weight:700; background:linear-gradient(135deg,#ffb347 0%,#ff7a18 100%); }
        .hero-image { border-radius:24px; overflow:hidden; box-shadow:0 20px 50px rgba(0,0,0,0.3); }
        .hero-image img { width:100%; height:100%; object-fit:cover; display:block; min-height:360px; }
        .features { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-top:18px; }
        .features div { background:rgba(255,255,255,0.14); padding:16px; border-radius:16px; }
        @media (max-width:900px) { .hero { grid-template-columns:1fr; } .features { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-card">
            <h1>Pizzaria Premium</h1>
            <p>Uma experiência acolhedora para quem busca sabor, rapidez e organização. Nossa plataforma centraliza pedidos, estoque e atendimento em um ambiente profissional.</p>
            <a class="btn" href="login.php">Acessar painel</a>
            <div class="features">
                <div><strong>Pedidos</strong><br>organize tudo em segundos</div>
                <div><strong>Clientes</strong><br>registre e acompanhe seus clientes</div>
                <div><strong>Estoque</strong><br>controle ingredientes e cardápio</div>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/imagens/pizza 1.jpeg" alt="Pizza artesanal da pizzaria">
        </div>
    </div>
</body>
</html>