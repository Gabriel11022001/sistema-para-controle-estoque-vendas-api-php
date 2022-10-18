<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Repositorios;

use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\ObjetoConexaoBancoDadosInvalidoException;
use PDO;

class CategoriaProdutoRepositorio implements IRepositorio
{
    private PDO $conexaoBancoDados;
    private string $nomeTabela;

    public function __construct(PDO $conexaoBancoDados)
    {
        if ($conexaoBancoDados == null) {
            throw new ObjetoConexaoBancoDadosInvalidoException('O objeto que representa a conexão com o banco de dados não deve ser nulo!');
        }
        $this->conexaoBancoDados = $conexaoBancoDados;
    }
    /**
     * @param array $dadosEntidade
     * @return bool
     * Método da camada de repositório para salvar
     * os dados de uma categoria no banco de dados.
     * Caso a categoria seja salva com sucesso, o método retorna true, caso
     * contrário, o método retorna false.
     */
    public function salvar(array $dadosEntidade): bool
    {
        $query = 'INSERT INTO tbl_categorias_produtos(categoria_produto_descricao, categoria_produto_status) VALUES(:categoria_produto_descricao, :categoria_produto_status);';
        $stmt = $this->conexaoBancoDados->prepare($query);
        $stmt->bindValue(':categoria_produto_descricao', $dadosEntidade['descricao']);
        $stmt->bindValue(':categoria_produto_status', $dadosEntidade['status'], PDO::PARAM_BOOL);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
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
        $query = 'SELECT * FROM tbl_categorias_produtos;';
        $stmt = $this->conexaoBancoDados->prepare($query);
        $stmt->execute();
        $categoriasDados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categoriasDados;
    }
}