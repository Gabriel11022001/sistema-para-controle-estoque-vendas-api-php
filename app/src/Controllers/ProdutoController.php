<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Controllers;

use GabrielSantos\SistemaControleEstoqueVendas\Servicos\ProdutoServico;

class ProdutoController
{
    public static function cadastrarProduto(): void
    {
        $produtoServico = new ProdutoServico();
        echo json_encode($produtoServico->cadastrarProduto());
    }
    public static function buscarTodosProdutos(): void
    {
        $produtoServico = new ProdutoServico();
        echo json_encode($produtoServico->buscarTodosProdutos());
    }
    public static function buscarProdutoPeloId(): void
    {
        $produtoServico = new ProdutoServico();
        echo json_encode($produtoServico->buscarProdutoPeloId());
    }
    public static function editarProduto(): void
    {
        $produtoServico = new ProdutoServico();
        echo json_encode($produtoServico->editarProduto());
    }
}