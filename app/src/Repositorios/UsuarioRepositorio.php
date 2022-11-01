<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Repositorios;

use PDO;

class UsuarioRepositorio implements IRepositorioAlteraStatus
{
    private PDO $conexaoBancoDados;

    public function __construct(PDO $pdo)
    {
        $this->conexaoBancoDados = $pdo;
    }
    public function salvar(array $dadosEntidade): bool
    {
        return true;
    }
    public function editar(array $dadosEntidade): bool
    {
        return true;
    }
    public function alterarStatus(int $id, bool $statusAtual): bool
    {
        return true;
    }
    public function buscarPeloId(int $id): array
    {
        return [];
    }
    public function buscarTodos(): array
    {
        $query = 'SELECT * FROM tbl_usuarios;';
        $stmt = $this->conexaoBancoDados->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}