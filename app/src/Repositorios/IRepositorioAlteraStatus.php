<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Repositorios;

interface IRepositorioAlteraStatus extends IRepositorio
{
    public function alterarStatus(int $id, bool $statusAtual): bool;
}