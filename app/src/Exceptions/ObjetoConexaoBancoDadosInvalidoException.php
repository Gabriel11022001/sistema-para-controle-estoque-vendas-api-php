<?php

namespace  GabrielSantos\SistemaControleEstoqueVendas\Exceptions;

use Exception;

class ObjetoConexaoBancoDadosInvalidoException extends Exception
{
    public function __construct(string $mensagem)
    {
        parent::__construct($mensagem);
    }
}