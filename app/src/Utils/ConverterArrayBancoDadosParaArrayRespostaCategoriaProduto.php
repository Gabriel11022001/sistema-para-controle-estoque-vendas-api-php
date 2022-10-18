<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Utils;

class ConverterArrayBancoDadosParaArrayRespostaCategoriaProduto extends ConverterArrayBancoDadosParaArrayResposta
{
    public static function converter(array $arrayBancoDados): array
    {
        $arrayConvertido = [];
        for ($i = 0; $i < count($arrayBancoDados); $i++) {
            $categoria = [
                'id' => $arrayBancoDados[$i]['categoria_produto_id'],
                'descricao' => $arrayBancoDados[$i]['categoria_produto_descricao'],
                'status' => $arrayBancoDados[$i]['categoria_produto_status'] === 1 ? true : false
            ];
            $arrayConvertido[] = $categoria;
        }
        return $arrayConvertido;
    }
    public static function converterArrayUnicoComDadosBuscadosPeloId(array $dadosCategoria): array
    {
        return [
            'id' => $dadosCategoria['categoria_produto_id'],
            'descricao' => $dadosCategoria['categoria_produto_descricao'],
            'status' => $dadosCategoria['categoria_produto_status']
        ];
    }
}