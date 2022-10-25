<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Utils;

class ConverterArrayBancoDadosParaArrayRespostaProduto extends ConverterArrayBancoDadosParaArrayResposta
{
    public static function converter(array $arrayBancoDados): array
    {
        $produtos = [];
        foreach ($arrayBancoDados as $produtoBancoDados) {
            $produto = [
                'id' => $produtoBancoDados['produto_id'],
                'nome' => $produtoBancoDados['produto_nome'],
                'codigoBarras' => $produtoBancoDados['produto_codigo_barras'],
                'precoVenda' => $produtoBancoDados['produto_preco_venda'],
                'quantidadeUnidadesEmEstoque' => $produtoBancoDados['produto_qtd_unidades_estoque'],
                'status' => $produtoBancoDados['produto_status'] === 1,
                'categoria' => [
                    'id' => $produtoBancoDados['categoria_produto_id'],
                    'descricao' => $produtoBancoDados['categoria_produto_descricao'],
                    'status' => $produtoBancoDados['categoria_produto_status'] === 1
                ]
            ];
            $produtos[] = $produto;
        }
        return $produtos;
    }
}