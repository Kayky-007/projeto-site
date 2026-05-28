<?php

require_once "Produto.php";

$produto = new Produto();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id_produto'];

    // Busca o produto antes de deletar para pegar o nome da imagem
    $dados = $produto->buscarPorId($id);

    // Se tiver imagem, apaga o arquivo da pasta
    if (!empty($dados['imagem_produto'])) {
        $caminho = "../img/" . $dados['imagem_produto'];

        if (file_exists($caminho)) {
            unlink($caminho);
        }
    }

    $produto->deletar($id);

}

header("Location: ../produtos.php");
exit;