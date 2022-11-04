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
        $query = 'INSERT INTO tbl_usuarios(usuario_nome, usuario_login, usuario_senha, usuario_nivel_acesso) VALUES(:usuario_nome, :usuario_login, :usuario_senha, :usuario_nivel_acesso);';
        $stmt = $this->conexaoBancoDados->prepare($query);
        $stmt->bindValue(':usuario_nome', $dadosEntidade['nome']);
        $stmt->bindValue(':usuario_login', $dadosEntidade['login']);
        $stmt->bindValue(':usuario_senha', md5($dadosEntidade['senha']));
        $stmt->bindValue(':usuario_nivel_acesso', $dadosEntidade['nivelAcesso'], PDO::PARAM_INT);
        return $stmt->execute();
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