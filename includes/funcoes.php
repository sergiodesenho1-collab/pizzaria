<?php
function verificarSenha($senhaInformada, $senhaArmazenada) {
    if (empty($senhaArmazenada)) {
        return false;
    }

    if (password_verify($senhaInformada, $senhaArmazenada)) {
        return true;
    }

    return md5($senhaInformada) === $senhaArmazenada;
}

function mensagem($tipo, $texto) {
    return '<div class="alert alert-' . htmlspecialchars($tipo) . '">' . htmlspecialchars($texto) . '</div>';
}

function isAdmin($nivel) {
    return $nivel === 'Administrador';
}