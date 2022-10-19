<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Utils;

class ConfiguraEnctypeEContentTypeApp
{
    public static function configurarEnctypeEContentType(): void
    {
        header("Content-Type: application/json; charset=utf-8");
    }
}