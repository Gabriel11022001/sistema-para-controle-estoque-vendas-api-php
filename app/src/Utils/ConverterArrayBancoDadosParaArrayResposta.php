<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Utils;

abstract class ConverterArrayBancoDadosParaArrayResposta
{
    abstract public static function converter(array $arrayBancoDados): array;
}