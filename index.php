<?php

require_once 'autoload.php';

use GabrielSantos\SistemaControleEstoqueVendas\Utils\{
    ConfiguraEnctypeEContentTypeApp,
    LiberaCors,
    DefineTimezoneDefault
};
use GabrielSantos\SistemaControleEstoqueVendas\Controllers\CategoriaProdutoController;

LiberaCors::liberarCors();
ConfiguraEnctypeEContentTypeApp::configurarEnctypeEContentType();
DefineTimezoneDefault::definirTimezoneDefault();
// Endpoint da api.
$endpoint = $_SERVER['REQUEST_URI'];
// Método HTTP da requisição.
$metodo = $_SERVER['REQUEST_METHOD'];
if ($endpoint === '/api/categoria-produto' && $metodo === 'POST') {
    // Cadastrar a categoria de produto.
    CategoriaProdutoController::cadastrarCategoriaDeProduto();
} elseif ($endpoint === '/api/categoria-produto' && $metodo === 'GET') {
    // Buscar todas as categorias de produtos.
    CategoriaProdutoController::buscarTodasCategoriasDeProduto();
} elseif ($endpoint === '/api/categoria-produto/buscar-pelo-id' && $metodo === 'GET') {
    // Buscar a categoria de produto pelo id.
} elseif ($endpoint === '/api/categoria-produto' && $metodo === 'PUT') {
    // Editar a categoria de produto.
} elseif ($endpoint === '/api/categoria-produto/alterar-status' && $metodo === 'PUT') {
    // Alterar o status da categoria de produto.
} else {
    // Caso o usuário faça uma requisição para um endpoint inválido!
    echo json_encode([
        'status' => 404,
        'mensagem' => 'Caminho não encontrado!'
    ]);
}
