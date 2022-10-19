<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Servicos;

use Exception;
use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\DadosFormularioInvalidoException;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\CategoriaProdutoRepositorio;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\ConexaoBancoDados;
use GabrielSantos\SistemaControleEstoqueVendas\Utils\ConverterArrayBancoDadosParaArrayRespostaCategoriaProduto;
use GabrielSantos\SistemaControleEstoqueVendas\Utils\Json;

class CategoriaProdutoServico
{
    public function salvarCategoriaDeProduto(): array
    {
        $resposta = [];
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if ($pdo === null) {
            return [
                'status' => 500,
                'conteudo' => 'Ocorreu um erro ao tentar-se realizar a conexão com o banco de dados!'
            ];
        }
        // Iniciar transação.
        $pdo->beginTransaction();
        try {
            // Validando os dados enviados para o servidor.
            $categoriaObjeto = Json::converterJsonEmObjeto();
            if (empty($categoriaObjeto->descricao) || empty($categoriaObjeto->status)) {
                throw new DadosFormularioInvalidoException('Informe os campos obrigatórios!');
            }
            $categoriaProdutoDados = [
                'id' => 0,
                'descricao' => $categoriaObjeto->descricao,
                'status' => $categoriaObjeto->status
            ];
            $categoriaProdutoRepositorio = new CategoriaProdutoRepositorio($pdo);
            // Invocando o método para salvar a categoria do produto no banco de dados.
            $resultadoCadastrarCategoriaProduto = $categoriaProdutoRepositorio
                ->salvar($categoriaProdutoDados);
            if ($resultadoCadastrarCategoriaProduto) {
                $resposta['status'] = 201;
                $categoriaProdutoDados['id'] = $pdo->lastInsertId();
                $resposta['conteudo'] = $categoriaProdutoDados;
            } else {
                $resposta['status'] = 500;
                $resposta['conteudo'] = 'Ocorreu um erro ao tentar-se cadastrar essa categoria no 
                banco de dados, tente novamente!';
            }
            // Comitando transação.
            $pdo->commit();
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
    public function buscarTodasCategoriasDeProdutos(): array
    {
        $resposta = [];
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if ($pdo === null) {
            return [
                'status' => 500,
                'conteudo' => 'Ocorreu um erro ao tentar-se realizar a conexão com o banco de dados!'
            ];
        }
        try {
            $categoriaProdutoRepositorio = new CategoriaProdutoRepositorio($pdo);
            $categoriasProdutosDados = $categoriaProdutoRepositorio
                ->buscarTodos();
            $categoriasProdutosDados = ConverterArrayBancoDadosParaArrayRespostaCategoriaProduto::converter($categoriasProdutosDados);
            $resposta['status'] = 200;
            $resposta['conteudo'] = $categoriasProdutosDados;
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = [];
        }
        return $resposta;
    }
    public function buscarCategoriaDeProdutoPeloId(): array
    {
        if (empty($_GET['id'])) {
            return [
                'status' => 500,
                'conteudo' => 'O id da categoria não foi informado!'
            ];
        }
        $id = intval($_GET['id']);
        if ($id === 0) {
            return [
                'status' => 500,
                'conteudo' => 'O id da categoria deve ser um valor numérico maior que 0 e inteiro!'
            ];
        }
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if ($pdo === null) {
            return [
                'status' => 500,
                'conteudo' => 'Ocorreu um erro ao tentar-se realizar a conexão com o banco de dados!'
            ];
        }
        $resposta = [];
        try {
            $categoriaDeProdutoRepositorio = new CategoriaProdutoRepositorio($pdo);
            $dadosCategoriaProduto = $categoriaDeProdutoRepositorio->buscarPeloId($id);
            $resposta['status'] = 200;
            $resposta['conteudo'] = $dadosCategoriaProduto;
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
        }
        return $resposta;
    }
}