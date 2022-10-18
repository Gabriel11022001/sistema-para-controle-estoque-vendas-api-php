<?php

spl_autoload_register(function (string $caminhoCompleto) {
    $caminhoCompleto = str_replace('GabrielSantos\\SistemaControleEstoqueVendas\\', 'app/src/', $caminhoCompleto);
    $caminhoCompleto = str_replace('\\', DIRECTORY_SEPARATOR, $caminhoCompleto);
    $caminhoCompleto .= '.php';
    if (file_exists($caminhoCompleto)) {
        require_once $caminhoCompleto;
    }
});