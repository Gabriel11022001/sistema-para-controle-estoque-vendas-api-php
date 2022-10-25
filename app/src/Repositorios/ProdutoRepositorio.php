<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Repositorios;

use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\ObjetoConexaoBancoDadosInvalidoException;
use PDO;

class ProdutoRepositorio implements IRepositorio
{
    private PDO $conexaoBancoDados;

    public function __construct(PDO $conexaoBancoDados)
    {
        if ($conexaoBancoDados == null) {
            throw new ObjetoConexaoBancoDadosInvalidoException('O objeto que representa a conexão com o banco de dados não deve ser nulo!');
        }
        $this->conexaoBancoDados = $conexaoBancoDados;
    }
    public function salvar(array $dadosEntidade): bool
    {
        $query = 'INSERT INTO tbl_produtos(produto_nome, produto_descricao, produto_codigo_barras,
                         produto_preco_venda, produto_qtd_unidades_estoque, produto_status,
                         categoria_produto_id) VALUES(:produto_nome, :produto_descricao, :produto_codigo_barras,
                         :produto_preco_venda, :produto_qtd_unidades_estoque, :produto_status,
                         :categoria_produto_id);';
        $stmt = $this->conexaoBancoDados->prepare($query);
        $stmt->bindValue(':produto_nome', $dadosEntidade['nome']);
        $stmt->bindValue(':produto_descricao', $dadosEntidade['descricao']);
        $stmt->bindValue(':produto_codigo_barras', $dadosEntidade['codigo_barras']);
        $stmt->bindValue(':produto_preco_venda', $dadosEntidade['preco_venda']);
        $stmt->bindValue(
            ':produto_qtd_unidades_estoque',
            $dadosEntidade['qtd_unidades_estoque'],
            PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':produto_status',
            $dadosEntidade['status'],
            PDO::PARAM_BOOL
        );
        $stmt->bindValue(
            ':categoria_produto_id',
            $dadosEntidade['categoria_produto_id'],
            PDO::PARAM_INT
        );
        return $stmt->execute();
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
        $query = 'SELECT * FROM tbl_produtos 
            INNER JOIN tbl_categorias_produtos
            ON tbl_produtos.categoria_produto_id = tbl_categorias_produtos.categoria_produto_id;';
        $stmt = $this->conexaoBancoDados->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}