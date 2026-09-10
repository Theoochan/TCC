<?php

$titulo = "Won by your own";

$categorias = Categoria::listar();

require __DIR__ . '/../../incluir/topo.php';
?>
<p class="rotulo text-[11px] uppercase tracking-[0.3em] text-[#a63a2a] mb-3">
    Coleção
</p>
<h1 class="titulo text-4xl md:text-5xl font-black leading-tight mb-7">
    Categorias
</h1>

<ul class="border-t border-[#0f1e3d]">
    <?php foreach($categorias as $categoria): ?>
        <li class="py-3 border-b border-[#0f1e3d]/20">
            <span class="rotulo uppercase tracking-[0.14em]">
                <?= escapar($categoria['nome'])?>
            </span>
            <p class="text-sm text-[#0f1e3d]/75">
                <?= escapar($categoria['descricao'])?>
            </p>
        </li>
    <?php endforeach; ?>
</ul>
<?php
require __DIR__ . '/../../incluir/rodape.php';
