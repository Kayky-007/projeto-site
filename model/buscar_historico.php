<?php

require_once __DIR__ . "/../conexao/Banco.php";
require_once __DIR__ . "/Cliente.php"; // ← faltava essa linha

$cliente = new Cliente();

$id      = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pedidos = $cliente->buscarHistorico($id);

header('Content-Type: application/json');
echo json_encode($pedidos);