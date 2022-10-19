<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Utils;

class Json
{
    public static function converterJsonEmObjeto(): object
    {
        $json = file_get_contents('php://input');
        $objetoConvertido = json_decode($json);
        return $objetoConvertido;
    }
}