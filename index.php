<?php

require_once 'autoload.php';

use GabrielSantos\SistemaControleEstoqueVendas\Servicos\CategoriaProdutoServico;
use GabrielSantos\SistemaControleEstoqueVendas\Controllers\CategoriaProdutoController;

header('Access-Control-Allow-Origin: *');
// Definir que o tipo de dados que será tratado nessa página será json.
header('Content-type: application/json');
// Definir o timezone para o horário de São Paulo.
date_default_timezone_set("America/Sao_Paulo");
// Endpoint da api.
$endpoint = $_SERVER['REQUEST_URI'];
// Método HTTP da requisição.
$metodo = $_SERVER['REQUEST_METHOD'];
if ($endpoint === '/api/categoria-produto' && $metodo === 'POST') {
    // Cadastrar a categoria de produto.
    CategoriaProdutoController::cadastrarCategoriaDeProduto();
} elseif ($endpoint == '/api/categoria-produto' && $metodo === 'GET') {
    // Buscar todas as categorias de produtos.
    CategoriaProdutoController::buscarTodasCategoriasDeProduto();
} elseif (str_contains($endpoint, '/api/categoria-produto/buscar-pelo-id') && $metodo === 'GET') {
    // Buscar a categoria de produto pelo id.
    CategoriaProdutoController::buscarCategoriaDeProdutoPeloId();
} elseif ($endpoint === '/api/categoria-produto' && $metodo === 'PUT') {
    // Editar a categoria de produto.
} elseif (str_contains($endpoint, '/api/categoria-produto/alterar-status') && $metodo === 'PUT') {
    // Alterar o status da categoria de produto.
} else {
    // Caso o usuário faça uma requisição para um endpoint inválido!
    echo json_encode([
        'status' => 404,
        'mensagem' => 'Caminho não encontrado!'
    ]);
}
