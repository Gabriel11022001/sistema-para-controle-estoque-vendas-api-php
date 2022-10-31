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
                // Comitando transação.
                $pdo->commit();
            } else {
                $resposta['status'] = 500;
                $resposta['conteudo'] = 'Ocorreu um erro ao tentar-se cadastrar essa categoria no 
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
            // Verificar se o id da categoria foi informado.
            if (empty($_GET['id'])) {
                return [
                    'status' => 500,
                    'conteudo' => 'O id da categoria não foi informado!'
                ];
            }
            // Verificar se o id informado é um número inteiro maior que 0!
            $id = intval($_GET['id']);
            if ($id === 0) {
                return [
                    'status' => 500,
                    'conteudo' => 'O id da categoria deve ser um valor numérico maior que 0 e inteiro!'
                ];
            }
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
    public function editarCategoriaProduto(): array
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
            if (!isset($categoriaObjeto->descricao) || !isset($categoriaObjeto->status)
            || $categoriaObjeto->descricao === '' || !isset($categoriaObjeto->id)) {
                throw new DadosFormularioInvalidoException('Informe os campos obrigatórios!');
            }
            $categoriaProdutoDados = [
                'id' => $categoriaObjeto->id,
                'descricao' => $categoriaObjeto->descricao,
                'status' => $categoriaObjeto->status
            ];
            $categoriaProdutoRepositorio = new CategoriaProdutoRepositorio($pdo);
            if (count($categoriaProdutoRepositorio->buscarPeloId($categoriaProdutoDados['id'])) === 0) {
                return [
                    'status' => 404,
                    'conteudo' => 'Não existe uma categoria de produto
                    com esse id cadastrado no banco de dados!'
                ];
            }
            if ($categoriaProdutoRepositorio->editar($categoriaProdutoDados)) {
                $resposta['status'] = 200;
                $resposta['conteudo'] = $categoriaProdutoDados;
                // Comitando a transação.
                $pdo->commit();
            } else {
                $resposta['status'] = 500;
                $resposta['conteudo'] = 'Ocorreu um erro ao tentar-se editar 
                essa categoria, tente novamente!';
                // Realizando o rollback da transação.
                $pdo->rollBack();
            }
        } catch (DadosFormularioInvalidoException $e) {
            $resposta['status'] = 400;
            $resposta['conteudo'] = $e->getMessage();
            // Realizando o rollback da transação.
            $pdo->rollBack();
        } catch (Exception $e) {
            // Realizar o rollback da transação.
            $pdo->rollBack();
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
        }
        return $resposta;
    }
    public function alterarStatusDaCategoriaDoProduto(): array
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
            if (empty($_GET['id']) || empty($_GET['statusAtual'])) {
                throw new Exception('Informe o id e o status atual da categoria!');
            }
            $id = $_GET['id'];
            $statusAtual = $_GET['statusAtual'];
            if ($statusAtual === 'true' || $statusAtual === 1) {
                $statusAtual = true;
            } else {
                $statusAtual = false;
            }
            $categoriaDeProdutoRepositorio = new CategoriaProdutoRepositorio($pdo);
            $categoriaAtualizarStatus = $categoriaDeProdutoRepositorio->buscarPeloId($id);
            if (count($categoriaAtualizarStatus) === 0) {
                throw new Exception('Não existe uma categoria cadastrada no banco de dados com esse id!');
            }
            if ($categoriaDeProdutoRepositorio->alterarStatus($id, $statusAtual)) {
                if ($statusAtual) {
                    $categoriaAtualizarStatus['status'] = false;
                } else {
                    $categoriaAtualizarStatus['status'] = true;
                }
                $resposta['status'] = 200;
                $resposta['conteudo'] = $categoriaAtualizarStatus;
            } else {
                $resposta['status'] = 500;
                $resposta['conteudo'] = 'Ocorreu um erro ao tentar-se alterar o status dessa categoria, tente novamente!';
            }
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
        }
        return $resposta;
    }
}