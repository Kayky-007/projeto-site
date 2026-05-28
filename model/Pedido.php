<?php

require_once __DIR__ . "/../conexao/Banco.php";

class Pedido {

    private $db;

    public function __construct() {
        $banco    = new Banco();
        $this->db = $banco->conexao;
    }

    // Busca todos os pedidos com nome do cliente e status
    public function listarTodos() {
        $resultado = $this->db->query("
            SELECT
                p.*,
                c.nome_cliente,
                s.nome_status,
                COUNT(i.id_item) AS total_itens
            FROM pedidos p
            JOIN clientes      c ON c.id_cliente = p.id_cliente
            JOIN status_pedido s ON s.id_status  = p.id_status
            LEFT JOIN itens_pedido i ON i.id_pedido = p.id_pedido
            GROUP BY p.id_pedido
            ORDER BY p.criado_em DESC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Busca um pedido pelo ID
    public function buscarPorId($id) {
        $id   = intval($id);
        $stmt = $this->db->prepare("
            SELECT
                p.*,
                c.nome_cliente,
                s.nome_status
            FROM pedidos p
            JOIN clientes      c ON c.id_cliente = p.id_cliente
            JOIN status_pedido s ON s.id_status  = p.id_status
            WHERE p.id_pedido = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Busca os itens de um pedido com nome e imagem do produto
    public function buscarItens($id_pedido) {
        $id_pedido = intval($id_pedido);
        $stmt = $this->db->prepare("
            SELECT
                i.quantidade,
                i.preco_unitario,
                (i.quantidade * i.preco_unitario) AS subtotal,
                pr.nome_produto,
                pr.imagem_produto
            FROM itens_pedido i
            JOIN produtos pr ON pr.id_produto = i.id_produto
            WHERE i.id_pedido = ?
        ");

        $stmt->bind_param("i", $id_pedido);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Cria um novo pedido e seus itens
    public function criar($dados, $itens) {

        // Inicia uma transação — ou salva tudo ou não salva nada
        $this->db->begin_transaction();

        try {

            $stmt = $this->db->prepare("
                INSERT INTO pedidos
                    (id_cliente, id_status, data_pedido, total_pedido, observacao_pedido)
                VALUES
                    (?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "iisds",
                $dados['id_cliente'],
                $dados['id_status'],
                $dados['data_pedido'],
                $dados['total_pedido'],
                $dados['observacao_pedido']
            );

            $stmt->execute();

            // Pega o ID do pedido que acabou de ser criado
            $id_pedido = $this->db->insert_id;

            // Insere cada item do pedido
            foreach ($itens as $item) {
                $stmtItem = $this->db->prepare("
                    INSERT INTO itens_pedido
                        (id_pedido, id_produto, quantidade, preco_unitario)
                    VALUES
                        (?, ?, ?, ?)
                ");

                $stmtItem->bind_param(
                    "iiid",
                    $id_pedido,
                    $item['id_produto'],
                    $item['quantidade'],
                    $item['preco_unitario']
                );

                $stmtItem->execute();

                // Desconta do estoque
                $stmtEstoque = $this->db->prepare("
                    UPDATE produtos
                    SET estoque_produto = estoque_produto - ?
                    WHERE id_produto = ?
                ");

                $stmtEstoque->bind_param("ii", $item['quantidade'], $item['id_produto']);
                $stmtEstoque->execute();
            }

            $this->db->commit();
            return $id_pedido;

        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    // Atualiza o status de um pedido
    public function atualizarStatus($id, $id_status) {
        $id        = intval($id);
        $id_status = intval($id_status);

        $stmt = $this->db->prepare("
            UPDATE pedidos SET id_status = ? WHERE id_pedido = ?
        ");

        $stmt->bind_param("ii", $id_status, $id);

        return $stmt->execute();
    }

    // Deleta um pedido (os itens são apagados automaticamente pelo CASCADE)
    public function deletar($id) {
        $id   = intval($id);
        $stmt = $this->db->prepare("
            DELETE FROM pedidos WHERE id_pedido = ?
        ");

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // Busca todos os clientes (para o select do modal)
    public function listarClientes() {
        $resultado = $this->db->query("
            SELECT id_cliente, nome_cliente FROM clientes ORDER BY nome_cliente ASC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Busca todos os produtos com estoque (para o select do modal)
    public function listarProdutos() {
        $resultado = $this->db->query("
            SELECT id_produto, nome_produto, preco_produto
            FROM produtos
            WHERE estoque_produto > 0
            ORDER BY nome_produto ASC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Busca todos os status (para o select)
    public function listarStatus() {
        $resultado = $this->db->query("
            SELECT * FROM status_pedido ORDER BY id_status ASC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

}