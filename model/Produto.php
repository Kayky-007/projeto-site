<?php

require_once __DIR__ . "/../conexao/Banco.php";


class Produto {

    private $db;

    public function __construct() {
        $banco    = new Banco();
        $this->db = $banco->conexao;
    }

    // Busca todos os produtos
    public function listarTodos() {
        $resultado = $this->db->query("
            SELECT p.*, c.nome_categoria
            FROM produtos p
            LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
            ORDER BY p.criado_em DESC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

public function criarCategoria($nome) {

    // Verifica se já existe
    $stmt = $this->db->prepare("
        SELECT id_categoria FROM categorias WHERE nome_categoria = ?
    ");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        return false;
    }

    // Cria a nova categoria
    $stmt = $this->db->prepare("
        INSERT INTO categorias (nome_categoria) VALUES (?)
    ");
    $stmt->bind_param("s", $nome);
    return $stmt->execute();
}


    // Busca um produto pelo ID
    public function buscarPorId($id) {
        $id   = intval($id);
        $stmt = $this->db->prepare("
            SELECT p.*, c.nome_categoria
            FROM produtos p
            LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
            WHERE p.id_produto = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Cria um novo produto
    public function criar($dados) {
        $stmt = $this->db->prepare("
            INSERT INTO produtos
                (nome_produto, preco_produto, id_categoria, estoque_produto, imagem_produto, descricao_produto)
            VALUES
                (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sdiiss",
            $dados['nome_produto'],
            $dados['preco_produto'],
            $dados['id_categoria'],
            $dados['estoque_produto'],
            $dados['imagem_produto'],
            $dados['descricao_produto']
        );

        return $stmt->execute();
    }

    // Atualiza um produto existente
    public function atualizar($id, $dados) {
        $id   = intval($id);
        $stmt = $this->db->prepare("
            UPDATE produtos SET
                nome_produto      = ?,
                preco_produto     = ?,
                id_categoria      = ?,
                estoque_produto   = ?,
                imagem_produto    = ?,
                descricao_produto = ?
            WHERE id_produto = ?
        ");

        $stmt->bind_param(
            "sdiissi",
            $dados['nome_produto'],
            $dados['preco_produto'],
            $dados['id_categoria'],
            $dados['estoque_produto'],
            $dados['imagem_produto'],
            $dados['descricao_produto'],
            $id
        );

        return $stmt->execute();
    }

    // Deleta um produto pelo ID
    public function deletar($id) {
        $id   = intval($id);
        $stmt = $this->db->prepare("
            DELETE FROM produtos WHERE id_produto = ?
        ");

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // Salva a imagem na pasta e devolve o nome do arquivo
    public function salvarImagem($arquivo) {
        $pasta = "./img/";

        // Cria a pasta se ela não existir ainda
        if (!is_dir($pasta)) {
            mkdir($pasta, 0755, true);
        }

        // Pega a extensão do arquivo (ex: jpg, png)
        $extensao  = pathinfo($arquivo['name'], PATHINFO_EXTENSION);

        // Cria um nome único para não conflitar com outros arquivos
        $nomeArquivo = uniqid("produto_") . "." . $extensao;

        // Move o arquivo para a pasta
        move_uploaded_file($arquivo['tmp_name'], $pasta . $nomeArquivo);

        return $nomeArquivo;
    }

    // Busca todas as categorias (para preencher o <select>)
    public function listarCategorias() {
        $resultado = $this->db->query("
            SELECT * FROM categorias ORDER BY nome_categoria ASC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

}