<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Utils;

class DefineTimezoneDefault
{
    public static function definirTimezoneDefault(): void
    {
        date_default_timezone_set("America/Sao_Paulo");
    }
}