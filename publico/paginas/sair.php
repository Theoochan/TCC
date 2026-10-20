<?php

//Encerra a sessão. Só aceita POST: se aceitasse link (GET), qualquer site
//poderia deslogar o usuário com uma <img src="/sair">. O POST passa pela
//verificação de CSRF do index.php antes de chegar aqui.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar('/');
}

$_SESSION = [];
session_destroy();

redirecionar('/');
