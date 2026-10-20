<?php

//Página inicial do painel. Por enquanto só existe para que a proteção das
//rotas /admin (index.php + protegido-admin.php) tenha o que proteger.
//O conteúdo real chega na E7.

$titulo = 'Painel';

require __DIR__ . '/../../../incluir/topo.php';
?>

<h1 class="titulo text-4xl md:text-5xl font-black mb-3">Painel</h1>
<p class="italic text-[#0f1e3d]/65">Em construção — cadastros e estoque chegam na E7.</p>

<?php
require __DIR__ . '/../../../incluir/rodape.php';
