<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Servicos;

use Exception;
use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\DadosFormularioInvalidoException;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\ConexaoBancoDados;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\ProdutoRepositorio;
use GabrielSantos\SistemaControleEstoqueVendas\Utils\ConverterArrayBancoDadosParaArrayRespostaProduto;
use GabrielSantos\SistemaControleEstoqueVendas\Utils\Json;

class ProdutoServico
{
    public function cadastrarProduto(): array
    {
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if (is_null($pdo)) {
            return [
                'status' => 500,
                'conteudo' => 'Ocorreu um erro ao tentar-se realizar a conexão com o banco de dados!'
            ];
        }
        $resposta = [];
        // Iniciar a transação.
        $pdo->beginTransaction();
        try {
            $produtoObjeto = Json::converterJsonEmObjeto();
            if (empty($produtoObjeto->nome) || empty($produtoObjeto->descricao)
            || empty($produtoObjeto->codigoBarras) || empty($produtoObjeto->precoVenda)
            || empty($produtoObjeto->quantidadeUnidadesEmEstoque)
            || empty($produtoObjeto->status) || empty($produtoObjeto->categoriaProdutoId)) {
                throw new DadosFormularioInvalidoException('Preencha todos os campos obrigatórios!');
            }
            $produtoRepositorio = new ProdutoRepositorio($pdo);
            $dadosProduto = [
                'nome' => $produtoObjeto->nome,
                'descricao' => $produtoObjeto->descricao,
                'codigo_barras' => $produtoObjeto->codigoBarras,
                'preco_venda' => $produtoObjeto->precoVenda,
                'qtd_unidades_estoque' => $produtoObjeto->quantidadeUnidadesEmEstoque,
                'status' => $produtoObjeto->status,
                'categoria_produto_id' => $produtoObjeto->categoriaProdutoId
            ];
            if ($produtoRepositorio->salvar($dadosProduto)) {
                $resposta['status'] = 201;
                $dadosProduto['id'] = $pdo->lastInsertId();
                $resposta['conteudo'] = $dadosProduto;
                // Comitando a transação.
                $pdo->commit();
            } else {
                $resposta['status'] = 500;
                $resposta['conteudo'] = 'Ocorreu um erro ao tentar-se cadastrar esse produto no 
                banco de dados, tente novamente!';
                // Realizando o rollback da transação.
                $pdo->rollBack();
            }
        } catch (DadosFormularioInvalidoException $e) {
            $resposta['status'] = 400;
            $resposta['conteudo'] = $e->getMessage();
            // Realizando o rollback da transação.
            $pdo->rollBack();
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
            // Realizando o rollback da transação.
            $pdo->rollBack();
        }
        return $resposta;
    }
    public function buscarTodosProdutos(): array
    {
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if (is_null($pdo)) {
            return [
                'status' => 500,
                'conteudo' => 'Ocorreu um erro ao tentar-se realizar a conexão com o banco de dados!'
            ];
        }
        $resposta = [];
        try {
            $produtoRepositorio = new ProdutoRepositorio($pdo);
            $produtos = $produtoRepositorio->buscarTodos();
            $produtos = ConverterArrayBancoDadosParaArrayRespostaProduto::converter($produtos);
            $resposta['status'] = 200;
            $resposta['conteudo'] = $produtos;
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
        }
        return $resposta;
    }
    public function buscarProdutoPeloId(): array
    {
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if (is_null($pdo)) {
            return [
                'status' => 500,
                'conteudo' => 'Ocorreu um erro ao tentar-se realizar a conexão com o banco de dados!'
            ];
        }
        $resposta = [];
        try {
            if (empty($_GET['id'])) {
                $resposta['status'] = 500;
                $resposta['conteudo'] = 'O id do produto não foi informado!';
            } else {
                $id = intval($_GET['id']);
                $produtoRepositorio = new ProdutoRepositorio($pdo);
                $dadosProduto = $produtoRepositorio->buscarPeloId($id);
                $resposta['status'] = 200;
                $resposta['conteudo'] = [
                    'id' => $dadosProduto['produto_id'],
                    'nome' => $dadosProduto['produto_nome']
                ];
            }
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
        }
        return $resposta;
    }
}