<?php

require_once __DIR__ . "/../model/Pedido.php";

$pedido = new Pedido();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$dados = $pedido->buscarPorId($id);
$itens = $pedido->buscarItens($id);

header('Content-Type: application/json');
echo json_encode([
    'pedido' => $dados,
    'itens'  => $itens
]);