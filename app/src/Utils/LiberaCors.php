<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Utils;

class LiberaCors
{
    /**
     * @return void
     * Método que libera o cors.
     */
    public static function liberarCors(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
        header('Access-Control-Allow-Methods:  POST, PUT, GET, DELETE');
    }
}