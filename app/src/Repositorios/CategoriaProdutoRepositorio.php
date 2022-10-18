<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Repositorios;

use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\ObjetoConexaoBancoDadosInvalidoException;
use PDO;

class CategoriaProdutoRepositorio implements IRepositorio
{
    private PDO $conexaoBancoDados;
    private string $nomeTabela;

    public function __construct(PDO $conexaoBancoDados, string $nomeTabela)
    {
        if ($conexaoBancoDados == null) {
            throw new ObjetoConexaoBancoDadosInvalidoException('O objeto que representa a conexão com o banco de dados não deve ser nulo!');
        }
        $this->conexaoBancoDados = $conexaoBancoDados;
        $this->nomeTabela = $nomeTabela;
    }
    public function salvar(array $dadosEntidade): bool
    {
        return false;
    }
    public function editar(array $dadosEntidade): bool
    {
        return false;
    }
    public function buscarPeloId(int $id): array
    {
        return [];
    }
    public function buscarTodos(): array
    {
        return [];
    }
}