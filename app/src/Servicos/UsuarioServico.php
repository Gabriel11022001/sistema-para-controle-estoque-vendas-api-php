<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Servicos;

use Exception;
use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\DadosFormularioInvalidoException;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\ConexaoBancoDados;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\UsuarioRepositorio;
use GabrielSantos\SistemaControleEstoqueVendas\Utils\Json;

class UsuarioServico 
{
    public function buscarTodosUsuarios(): array
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
            $usuarioRepositorio = new UsuarioRepositorio($pdo);
            $usuarios = $usuarioRepositorio->buscarTodos();
            $resposta['status'] = 200;
            $usuariosJson = [];
            foreach ($usuarios as $usuario) {
                $usuariosJson[] = [
                    'id' => $usuario['usuario_id'],
                    'nome' => $usuario['usuario_nome']
                ];
            }
            $resposta['conteudo'] = $usuariosJson;
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
        }
        return $resposta;
    }
    public function cadastrarUsuario(): array
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
        // Iniciando transação.
        $pdo->beginTransaction();
        try {
            $usuarioObjeto = Json::converterJsonEmObjeto();
            if (empty($usuarioObjeto->nome) || empty($usuarioObjeto->login)
            || empty($usuarioObjeto->senha) || empty($usuarioObjeto->nivelAcesso)) {
                throw new DadosFormularioInvalidoException('Preencha todos os campos obrigatórios!');
            }
            $usuario = [
                'id' => 0,
                'nome' => $usuarioObjeto->nome,
                'login' => $usuarioObjeto->login,
                'senha' => $usuarioObjeto->senha,
                'nivelAcesso' => $usuarioObjeto->nivelAcesso
            ];
            $usuarioRepositorio = new UsuarioRepositorio($pdo);
            if ($usuarioRepositorio->salvar($usuario)) {
                $id = $pdo->lastInsertId();
                $usuario['id'] = $id;
                $usuario['senha'] = md5($usuario['senha']);
                $resposta['status'] = 201;
                $resposta['conteudo'] = $usuario;
                // Comitando a transação
                $pdo->commit();
            } else {
                $resposta['status'] = 500;
                $resposta['conteudo'] = 'Ocorreu um erro ao tentar-se cadastrar o usuário no banco de dados, tente novamente!';
                // Realizando o rollback da transação.
                $pdo->rollBack();
            }
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
            // Realizando o rollback da transação.
            $pdo->rollBack();
        }
        return $resposta;
    }
}