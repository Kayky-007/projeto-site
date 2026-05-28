<?php

require_once __DIR__ . "/../conexao/Banco.php";

class Usuario {

    private $db;

    public function __construct() {
        $banco    = new Banco();
        $this->db = $banco->conexao;
    }

    // Busca o usuario pelo login e senha md5
    public function login($login, $senha) {
        $senhaMd5 = md5($senha);

        $stmt = $this->db->prepare("
            SELECT u.*, p.tipo_permissao
            FROM usuarios u
            JOIN permissoes p ON p.id_permissao = u.id_permissao
            WHERE u.login_usuario = ?
            AND u.senha_usuario   = ?
            AND u.ativo           = 1
        ");

        $stmt->bind_param("ss", $login, $senhaMd5);
        $stmt->execute();

        $usuario = $stmt->get_result()->fetch_assoc();

        // Se encontrou o usuario, atualiza o ultimo acesso
        if ($usuario) {
            $this->atualizarUltimoAcesso($usuario['id_usuario']);
        }

        return $usuario;
    }

    // Atualiza a data do ultimo acesso
    private function atualizarUltimoAcesso($id) {
        $stmt = $this->db->prepare("
            UPDATE usuarios SET ultimo_acesso = NOW() WHERE id_usuario = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

}