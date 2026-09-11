<?php

// ────────────────────────────────────────────────────────────────
// Carrega os modelos.
//
// Um modelo por tabela, cada um numa classe. Toda consulta SQL do
// sistema fica dentro deles — nenhuma na página.
//
// Conforme os modelos forem criados (entrega E1 em diante), basta
// acrescentar a linha aqui.
// ────────────────────────────────────────────────────────────────
//Produto
require __DIR__ . '/modelos/Produto.php';
require __DIR__ . '/modelos/Categoria.php';
// require __DIR__ . '/modelos/VarianteProduto.php';
// require __DIR__ . '/modelos/ProdutoCor.php';
// require __DIR__ . '/modelos/ImagemProduto.php';
// require __DIR__ . '/modelos/Cor.php';
// require __DIR__ . '/modelos/EntradaEstoque.php';

//Venda
// require __DIR__ . '/modelos/Venda.php';
// require __DIR__ . '/modelos/VendaItem.php';
// require __DIR__ . '/modelos/CarrinhoItem.php';
// require __DIR__ . '/modelos/Pagamento.php';

//Conta e apoio
// require __DIR__ . '/modelos/Usuario.php';
// require __DIR__ . '/modelos/FaixaFrete.php';