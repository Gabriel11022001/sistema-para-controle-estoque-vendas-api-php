<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Servicos;

use Exception;
use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\DadosFormularioInvalidoException;
use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\ParametroCabecalhoRequisicaoInvalidoException;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\CategoriaProdutoRepositorio;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\ConexaoBancoDados;
use GabrielSantos\SistemaControleEstoqueVendas\Utils\ConverterArrayBancoDadosParaArrayRespostaCategoriaProduto;

class CategoriaProdutoServico
{
    public function salvarCategoriaDeProduto(): array
    {
        $resposta = [];
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if ($pdo == null) {
            return [
                'status' => 500,
                'conteudo' => 'Ocorreu um erro ao tentar-se realizar a conexão com o banco de dados!'
            ];
        }
        // Iniciar transação.
        $pdo->beginTransaction();
        try {
            // Validando os dados enviados para o servidor.
            if (empty($_POST['descricao']) || empty($_POST['status'])) {
                throw new DadosFormularioInvalidoException('Preencha todos os campos obrigatórios!');
            }
            $categoriaProdutoDados = [
                'id' => 0,
                'descricao' => $_POST['descricao'],
                'status' => $_POST['status']
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
            // Salvar mensagem de erro da exceção no arquivo de log.
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
            // Realizando o rollback da transação.
            $pdo->rollBack();
            // Salvar mensagem de erro da exceção no arquivo de log.
        }
        return $resposta;
    }
    public function buscarTodasCategoriasDeProdutos(): array
    {
        $resposta = [];
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if ($pdo == null) {
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
            // Salvar mensagem de erro da exceção no arquivo de log.
        }
        return $resposta;
    }
    public function buscarCategoriaDeProdutoPeloId(): array
    {
        $resposta = [];
        $conexao = new ConexaoBancoDados();
        $pdo = $conexao->getConexao();
        if ($pdo == null) {
            return [
                'status' => 500,
                'conteudo' => 'Ocorreu um erro ao tentar-se realizar a conexão com o banco de dados!'
            ];
        }
        try {
            if (empty($_GET['id'])) {
                throw new ParametroCabecalhoRequisicaoInvalidoException(
                    'O id da categoria é um valor obrigatório para consulta!'
                );
            }
            // Pegar o id em formato de um número inteiro e verificar se o id é valido.
            $id = intval($_GET['id']);
            if ($id === 0) {
                throw new ParametroCabecalhoRequisicaoInvalidoException(
                    'O id da categoria deve ser um valor numérico do tipo inteiro!'
                );
            }
            $categoriaProdutoRepositorio = new CategoriaProdutoRepositorio($pdo);
            $dadosCategoria = $categoriaProdutoRepositorio->buscarPeloId($id);
            if (count($dadosCategoria) == 0) {
                $resposta['conteudo'] = [];
            } else {
                $resposta['conteudo'] = ConverterArrayBancoDadosParaArrayRespostaCategoriaProduto
                    ::converterArrayUnicoComDadosBuscadosPeloId($dadosCategoria);
            }
            $resposta['status'] = 200;
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
            // Salvar mensagem de erro da exceção no arquivo de log.
        }
        return $resposta;
    }
}