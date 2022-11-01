<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Servicos;

use Exception;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\ConexaoBancoDados;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\UsuarioRepositorio;

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
}