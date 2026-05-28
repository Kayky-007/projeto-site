<?php

date_default_timezone_set('America/Sao_Paulo');

class Banco {

    private $host     = "localhost";
    private $usuario  = "root";
    private $senha    = "";
    private $database = "gestao";

    public $conexao;

    public function __construct() {
        $this->conexao = new mysqli(
            $this->host,
            $this->usuario, 
            $this->senha,
            $this->database
        );

        if ($this->conexao->connect_error) {
            die("Erro ao conectar: " . $this->conexao->connect_error);
        }

        $this->conexao->set_charset("utf8mb4");
    }

}