<?php
//  Inclui esse arquivo no topo de cada página
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Atalho para usar nas páginas
$usuarioLogado = $_SESSION['usuario'];