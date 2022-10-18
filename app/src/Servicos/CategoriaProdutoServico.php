<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Servicos;

use Exception;
use GabrielSantos\SistemaControleEstoqueVendas\Exceptions\DadosFormularioInvalidoException;
use GabrielSantos\SistemaControleEstoqueVendas\Repositorios\CategoriaProdutoRepositorio;

class CategoriaProdutoServico
{
    public function salvarCategoriaDeProduto(): array
    {
        $resposta = [];
        try {
            if (empty($_POST['descricao']) || empty($_POST['status'])) {
                throw new DadosFormularioInvalidoException('Preencha todos os campos obrigatórios!');
            }
            $categoriaProdutoDados = [
                'descricao' => $_POST['descricao'],
                'status' => $_POST['status']
            ];
            $categoriaProdutoRepositorio = new CategoriaProdutoRepositorio(
                null,
                'tb_categorias_de_produtos'
            );
        } catch (DadosFormularioInvalidoException $e) {
            $resposta['status'] = 400;
            $resposta['conteudo'] = $e->getMessage();
        } catch (Exception $e) {
            $resposta['status'] = 500;
            $resposta['conteudo'] = $e->getMessage();
        }
        return $resposta;
    }
}