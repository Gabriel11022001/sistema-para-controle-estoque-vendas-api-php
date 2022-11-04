<?php

namespace GabrielSantos\SistemaControleEstoqueVendas\Controllers;

use GabrielSantos\SistemaControleEstoqueVendas\Servicos\UsuarioServico;

class UsuarioController
{
    public static function buscarTodosOsUsuarios(): void
    {
        $usuarioServico = new UsuarioServico();
        echo json_encode($usuarioServico->buscarTodosUsuarios());
    }
    public static function cadastrarUsuario(): void
    {
        $usuarioServico = new UsuarioServico();
        echo json_encode($usuarioServico->cadastrarUsuario());
    }
}