<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Repositorios;

use PDOException;
use PDO;

class ConexaoBancoDados
{
    private string $nomeBancoDados;
    private string $usuario;
    private string $senha;
    private int $porta;

    public function __construct()
    {
        $this->nomeBancoDados = 'db_api_sistema_controle_estoque_vendas';
        $this->usuario = 'root';
        $this->senha = 'root';
        $this->porta = 8000;
    }
    public function getConexao(): PDO|null
    {
        $conexaoBancoDados = null;
        try {
            $conexaoBancoDados = new PDO(
                'mysql:host=localhost:' . $this->porta . ';dbname=' . $this->nomeBancoDados,
                $this->usuario,
                $this->senha
            );
            $conexaoBancoDados->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            /**
             * Caso ocorra uma exceção ao tentar-se conectar com o banco
             * de dados, salvar a mensagem da exceção no arquivo de log.
             */
        }
        return $conexaoBancoDados;
    }
}