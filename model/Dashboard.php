<?php

require_once __DIR__ . "/../conexao/Banco.php";

class Dashboard {

    private $db;

    public function __construct() {
        $banco    = new Banco();
        $this->db = $banco->conexao;
    }

    // Totais dos cards de resumo
    public function resumo() {
        $dados = [];

        $dados['total_clientes'] = $this->db->query("
            SELECT COUNT(*) AS total FROM clientes
        ")->fetch_assoc()['total'];

        $dados['total_produtos'] = $this->db->query("
            SELECT COUNT(*) AS total FROM produtos
        ")->fetch_assoc()['total'];

        $dados['total_pedidos'] = $this->db->query("
            SELECT COUNT(*) AS total FROM pedidos
        ")->fetch_assoc()['total'];

        $dados['faturamento_total'] = $this->db->query("
            SELECT COALESCE(SUM(total_pedido), 0) AS total
            FROM pedidos
            WHERE id_status = 3
        ")->fetch_assoc()['total'];

        $dados['pedidos_pendentes'] = $this->db->query("
            SELECT COUNT(*) AS total FROM pedidos WHERE id_status = 1
        ")->fetch_assoc()['total'];

        $dados['estoque_baixo'] = $this->db->query("
            SELECT COUNT(*) AS total FROM produtos WHERE estoque_produto <= 10
        ")->fetch_assoc()['total'];

        return $dados;
    }

    // Pedidos por mês nos últimos 6 meses
    public function pedidosPorMes() {
        $resultado = $this->db->query("
            SELECT
                DATE_FORMAT(criado_em, '%m/%Y') AS mes,
                DATE_FORMAT(criado_em, '%Y-%m') AS mes_ordem,
                COUNT(*) AS total
            FROM pedidos
            WHERE criado_em >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mes_ordem, mes
            ORDER BY mes_ordem ASC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Faturamento por mês nos últimos 6 meses
    public function faturamentoPorMes() {
        $resultado = $this->db->query("
            SELECT
                DATE_FORMAT(criado_em, '%m/%Y') AS mes,
                DATE_FORMAT(criado_em, '%Y-%m') AS mes_ordem,
                COALESCE(SUM(total_pedido), 0) AS total
            FROM pedidos
            WHERE id_status = 3
            AND criado_em >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mes_ordem, mes
            ORDER BY mes_ordem ASC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Produtos com estoque baixo (até 10 unidades)
    public function estoqueBaixo() {
        $resultado = $this->db->query("
            SELECT
                p.nome_produto,
                p.estoque_produto,
                p.imagem_produto,
                c.nome_categoria
            FROM produtos p
            LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
            WHERE p.estoque_produto <= 10
            ORDER BY p.estoque_produto ASC
            LIMIT 6
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Pedidos por status (para o gráfico de rosca)
    public function pedidosPorStatus() {
        $resultado = $this->db->query("
            SELECT
                s.nome_status,
                COUNT(p.id_pedido) AS total
            FROM status_pedido s
            LEFT JOIN pedidos p ON p.id_status = s.id_status
            GROUP BY s.id_status, s.nome_status
            ORDER BY s.id_status ASC
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Top 5 produtos mais vendidos
    public function topProdutos() {
        $resultado = $this->db->query("
            SELECT
                pr.nome_produto,
                pr.imagem_produto,
                SUM(i.quantidade) AS total_vendido,
                SUM(i.quantidade * i.preco_unitario) AS total_faturado
            FROM itens_pedido i
            JOIN produtos pr ON pr.id_produto = i.id_produto
            JOIN pedidos p   ON p.id_pedido   = i.id_pedido
            WHERE p.id_status != 4
            GROUP BY pr.id_produto, pr.nome_produto, pr.imagem_produto
            ORDER BY total_vendido DESC
            LIMIT 5
        ");

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

}