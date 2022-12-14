<?php

require_once 'autoload.php';

use GabrielSantos\SistemaControleEstoqueVendas\Utils\{
    ConfiguraEnctypeEContentTypeApp,
    LiberaCors,
    DefineTimezoneDefault
};
use GabrielSantos\SistemaControleEstoqueVendas\Controllers\{
    CategoriaProdutoController,
    ProdutoController,
    UsuarioController
};

// Liberar o CORS.
LiberaCors::liberarCors();
// Definir o contentType como application/json e o enctype como utf-8.
ConfiguraEnctypeEContentTypeApp::configurarEnctypeEContentType();
// Definir o timezone default para América(São Paulo).
DefineTimezoneDefault::definirTimezoneDefault();
// Endpoint da api.
$endpoint = $_SERVER['REQUEST_URI'];
$endpoint = str_replace('/index.php', '', $endpoint);
// Método HTTP da requisição.
$metodo = $_SERVER['REQUEST_METHOD'];
if ($endpoint === '/api/categoria-produto' && $metodo === 'POST') {
    // Cadastrar a categoria de produto.
    CategoriaProdutoController::cadastrarCategoriaDeProduto();
} elseif ($endpoint === '/api/categoria-produto' && $metodo === 'GET') {
    // Buscar todas as categorias de produtos.
    CategoriaProdutoController::buscarTodasCategoriasDeProduto();
} elseif (str_contains($endpoint, '/api/categoria-produto/buscar-pelo-id') && $metodo === 'GET') {
    // Buscar a categoria de produto pelo id.
    CategoriaProdutoController::buscarCategoriaDeProdutoPeloId();
} elseif ($endpoint === '/api/categoria-produto' && $metodo === 'PUT') {
    // Editar a categoria de produto.
    CategoriaProdutoController::editarCategoriaDeProduto();
} elseif (str_contains($endpoint, '/api/categoria-produto/alterar-status') && $metodo === 'GET') {
    // Alterar o status da categoria de produto.
    CategoriaProdutoController::alterarStatusDaCategoria();
} elseif ($endpoint === '/api/produto' && $metodo === 'POST') {
    // Cadastrar produto no banco de dados.
    ProdutoController::cadastrarProduto();
} elseif ($endpoint === '/api/produto' && $metodo === 'GET') {
    // Buscar todos os produtos cadastrados no banco de dados.
    ProdutoController::buscarTodosProdutos();
} elseif (str_contains($endpoint, '/api/produto/buscar-pelo-id') && $metodo === 'GET') {
    // Buscar produto pelo id.
    ProdutoController::buscarProdutoPeloId();
} elseif ($endpoint === '/api/produto' && $metodo === 'PUT') {
    // Editar os dados de um produto.
    ProdutoController::editarProduto();
} elseif ($endpoint === '/api/usuario' && $metodo === 'GET') {
    // Buscar todos os usuários cadastrados no banco de dados.
    UsuarioController::buscarTodosOsUsuarios();
} elseif ($endpoint === '/api/usuario' && $metodo === 'POST') {
    // Cadastrar usuário no banco de dados.
    UsuarioController::cadastrarUsuario();
} else {
    // Caso o usuário faça uma requisição para um endpoint inválido!
    echo json_encode([
        'status' => 404,
        'mensagem' => 'Caminho não encontrado!'
    ]);
}
