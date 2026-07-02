<?php
require_once __DIR__ . '/config.php';

$siteName = SITE_NAME;
$siteSlogan = 'Sabor, rapidez e organização';

if (isset($pdo)) {
    $cfgStmt = $pdo->query('SELECT chave, valor FROM configuracoes');
    while ($cfg = $cfgStmt->fetch(PDO::FETCH_ASSOC)) {
        if ($cfg['chave'] === 'site_name') {
            $siteName = $cfg['valor'];
        }
        if ($cfg['chave'] === 'site_slogan') {
            $siteSlogan = $cfg['valor'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteName) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
<div class="topbar">
    <button type="button" class="mobile-toggle" aria-label="Abrir menu">☰ Menu</button>
    <button type="button" class="theme-toggle" aria-label="Alternar tema">🌙 Tema</button>
</div>
<div class="app-shell">
    <?php require_once __DIR__ . '/menu.php'; ?>
    <main class="main-content">