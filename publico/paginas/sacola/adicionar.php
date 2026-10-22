<?php

//Recebe o "Adicionar à sacola" da página de produto. Só ação, sem HTML.
//Só por POST (D-44); o login é exigido pelo index.php para toda rota /sacola.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/');
}

$erros = CarrinhoItem::incluir(
    $_SESSION['usuario_id'],
    $_POST['variante_id'] ?? '',
    $_POST['qtde'] ?? ''
);

if (count($erros) === 0) {
    avisar('Adicionado à sacola.');
} else {
    avisar(implode(' ', $erros));
}

//Volta para o produto. O caminho /produto é fixo, escrito aqui: os números
//vindos do formulário só preenchem os parâmetros, e não conseguem mandar
//para fora da loja.
redirecionar('/produto?' . http_build_query([
    'id'  => (int) ($_POST['produto_id'] ?? 0),
    'cor' => (int) ($_POST['cor_id'] ?? 0),
]));
