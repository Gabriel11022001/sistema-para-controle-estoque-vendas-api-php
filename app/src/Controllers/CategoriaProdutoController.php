<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Controllers;

use GabrielSantos\SistemaControleEstoqueVendas\Servicos\CategoriaProdutoServico;

class CategoriaProdutoController
{
    /**
     * @return void
     * Método da camada de controle para cadastrar uma categoria de produto
     * no banco de dados.
     * endpoint -> /api/categoria-produto
     * método HTTP -> POST
     */
    public static function cadastrarCategoriaDeProduto(): void
    {
        $categoriaProdutoServico = new CategoriaProdutoServico();
        echo json_encode($categoriaProdutoServico->salvarCategoriaDeProduto());
    }
    public static function buscarTodasCategoriasDeProduto(): void
    {
        $categoriaProdutoServico = new CategoriaProdutoServico();
        echo json_encode($categoriaProdutoServico->buscarTodasCategoriasDeProdutos());
    }
    public static function buscarCategoriaDeProdutoPeloId(): void
    {
        $categoriaProdutoServico = new CategoriaProdutoServico();
        echo json_encode($categoriaProdutoServico->buscarCategoriaDeProdutoPeloId());
    }
}