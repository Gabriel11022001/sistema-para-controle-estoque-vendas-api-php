<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Repositorios;

interface IRepositorio
{
    public function salvar(array $dadosEntidade): bool;
    public function buscarTodos(): array;
    public function buscarPeloId(int $id): array;
    public function editar(array $dadosEntidade): bool;
}