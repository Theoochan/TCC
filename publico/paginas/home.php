<?php

$titulo = "Won by your own";

//Filtro de categoria
$categoria = null;
$categoria_id = null;
if(isset($_GET['categoria'])){
    $categoria_id = $_GET['categoria'];
    $categoria = Categoria::buscar($categoria_id);

    if ($categoria === null) {
        redirecionar('/');
    }
    
    $titulo = $categoria['nome'];
    
}

$categorias = Categoria::listar();
$produtos = Produto::listar($categoria_id);

require __DIR__ . '/../../incluir/topo.php';
?>

<p class="rotulo text-[11px] uppercase tracking-[0.3em] text-[#a63a2a] mb-3">
    Coleção
</p>

<h1 class="titulo text-4xl md:text-5xl font-black leading-tight mb-8">
    <?php if ($categoria !== null): ?>
        <?= escapar($categoria['nome'])?>
    <?php else: ?>
        Escolha seu campo
    <?php endif; ?>
</h1>
<!--Seleção do filtro de categoria-->
<nav class="rotulo flex flex-wrap gap-x-6 gap-y-2 text-xs uppercase tracking-[0.14em] border-b border-[#0f1e3d]/20 pb-4 mb-10">
    <a href="/" class="<?= $categoria_id === null ? 'text-[#a63a2a]' : 'hover:underline' ?>">
        Tudo
    </a>
    <?php foreach ($categorias as $i => $item): ?>
        <a href="/?categoria=<?=escapar($item['id'])?>" class="<?= $categoria_id == $item['id'] ? 'text-[#a63a2a]' : 'hover:underline' ?>">
            <?=escapar($item['nome'])?>
        </a>
    <?php endforeach;?>
</nav>
    <!--Listagem dos Produtos-->
<?php if (count($produtos) == 0): ?>
    <p class="italic text-[#0f1e3d]/70">
        Nenhum produto nesta categoria
    </p>
<?php else: ?>
    <ul class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach($produtos as $i => $item): ?>
            <li>
                <a href="/produto?id=<?= escapar($item['id']) ?>" class="block group">
                    <div class="aspect-[3/4] bg-[#e8dcc0] mb-3"></div>
                    <h2 class="titulo text-lg font-bold group-hover:text-[#a63a2a]">
                        <?= escapar($item['nome']) ?>
                    </h2>
                    <p class="rotulo text-sm mt-1">
                        <?= escapar(dinheiro($item['valor'])) ?>
                    </p>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php
require __DIR__ . '/../../incluir/rodape.php';
