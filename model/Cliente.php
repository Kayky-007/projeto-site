<?php

require_once __DIR__ . "/../conexao/Banco.php";

class Cliente {

    private $db;

    public function __construct() {
        $banco    = new Banco();
        $this->db = $banco->conexao;
    }

    // Busca todos os clientes
    public function listarTodos() {
        $resultado = $this->db->query("
            SELECT c.*,
                COUNT(p.id_pedido) AS total_pedidos
            FROM clientes c
            LEFT JOIN pedidos p ON p.id_cliente = c.id_cliente
            GROUP BY c.id_cliente
            ORDER BY c.criado_em DESC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Busca um cliente pelo ID
    public function buscarPorId($id) {
        $id   = intval($id);
        $stmt = $this->db->prepare("
            SELECT * FROM clientes WHERE id_cliente = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Busca o histórico de pedidos de um cliente
    public function buscarHistorico($id_cliente) {
        $id_cliente = intval($id_cliente);
        $stmt = $this->db->prepare("
            SELECT
                p.id_pedido,
                p.data_pedido,
                p.total_pedido,
                s.nome_status
            FROM pedidos p
            JOIN status_pedido s ON s.id_status = p.id_status
            WHERE p.id_cliente = ?
            ORDER BY p.data_pedido DESC
        ");

        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Cria um novo cliente
    public function criar($dados) {
        $stmt = $this->db->prepare("
            INSERT INTO clientes
                (nome_cliente, tipo_cliente, documento_cliente, email_cliente, telefone_cliente, cep_cliente, endereco_cliente, observacao_cliente)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssss",
            $dados['nome_cliente'],
            $dados['tipo_cliente'],
            $dados['documento_cliente'],
            $dados['email_cliente'],
            $dados['telefone_cliente'],
            $dados['cep_cliente'],
            $dados['endereco_cliente'],
            $dados['observacao_cliente']
        );

        return $stmt->execute();
    }

    // Atualiza um cliente existente
    public function atualizar($id, $dados) {
        $id   = intval($id);
        $stmt = $this->db->prepare("
            UPDATE clientes SET
                nome_cliente       = ?,
                tipo_cliente       = ?,
                documento_cliente  = ?,
                email_cliente      = ?,
                telefone_cliente   = ?,
                cep_cliente        = ?,
                endereco_cliente   = ?,
                observacao_cliente = ?
            WHERE id_cliente = ?
        ");

        $stmt->bind_param(
            "ssssssssi",
            $dados['nome_cliente'],
            $dados['tipo_cliente'],
            $dados['documento_cliente'],
            $dados['email_cliente'],
            $dados['telefone_cliente'],
            $dados['cep_cliente'],
            $dados['endereco_cliente'],
            $dados['observacao_cliente'],
            $id
        );

        return $stmt->execute();
    }

    // Deleta um cliente pelo ID
    public function deletar($id) {
        $id   = intval($id);
        $stmt = $this->db->prepare("
            DELETE FROM clientes WHERE id_cliente = ?
        ");

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

}