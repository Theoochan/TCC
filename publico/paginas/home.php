<?php

$titulo = "Won by your own";

// URL
$categoria_id = $_GET['categoria'] ?? null;
$cor_id = $_GET['cor'] ?? null;
$busca = trim($_GET['busca'] ?? '') ;

if ($busca === '') {
    $busca = null;
}

$categorias = Categoria::listar();
$cores = Cor::listar();

//Filtro de categoria
$categoria = null;
if($categoria_id !== null){
    
    $categoria = Categoria::buscar($categoria_id);

    if ($categoria === null) {
        redirecionar(urlVitrine(null, $cor_id, $busca));
    }
    
    $titulo = $categoria['nome'];
    
}

// filtro de cor
$cor = null;
if ($cor_id !== null) {
    foreach($cores as $item){
        if($item['id'] == $cor_id){
            $cor = $item;
        }
    }
    
    if($cor === null){
        redirecionar(urlVitrine($categoria_id, null, $busca));
    }
}

$produtos = Produto::listar([
    'categoria' => $categoria_id,
    'cor' => $cor_id,
    'busca' => $busca
]);

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
<nav class="rotulo flex flex-wrap gap-x-6 gap-y-2 text-xs uppercase tracking-[0.14em] border-b border-[#0f1e3d]/20 pb-4 mb-5">
    <a href="<?= escapar(urlVitrine(null, $cor_id, $busca)) ?>" class="<?= $categoria_id === null ? 'text-[#a63a2a]' : 'hover:underline' ?>">
        Tudo
    </a>
    <?php foreach ($categorias as $i => $item): ?>
        <a href="<?= escapar(urlVitrine($item['id'], $cor_id, $busca)) ?>" class="<?= $categoria_id == $item['id'] ? 'text-[#a63a2a]' : 'hover:underline' ?>">
            <?=escapar($item['nome'])?>
        </a>
    <?php endforeach;?>
</nav>

<!-- Filtro de cor -->
<nav class="flex flex-wrap items-center gap-3 mb-10">
    <a href="<?= escapar(urlVitrine($categoria_id, null, $busca)) ?>"
       class="rotulo text-xs uppercase tracking-[0.14em] mr-2 <?= $cor_id === null ? 'text-[#a63a2a]' : 'hover:underline' ?>">
        Todas as cores
    </a>
    <?php foreach ($cores as $item): ?>
        <a href="<?= escapar(urlVitrine($categoria_id, $item['id'], $busca)) ?>"
           title="<?= escapar($item['nome']) ?>"
           aria-label="<?= escapar($item['nome']) ?>"
           class="w-7 h-7 rounded-full border border-[#0f1e3d]/30 <?= $cor_id == $item['id'] ? 'ring-2 ring-offset-2 ring-[#a63a2a]' : '' ?>"
           style="background: <?= escapar(Cor::amostra($item)) ?>"></a>
    <?php endforeach; ?>
</nav>

<!--Busca-->
<?php if ($busca !== null): ?>
    <p class="mb-8">
        Resultados para <strong>"<?= escapar($busca) ?>"</strong> ·
        <a href="<?= escapar(urlVitrine($categoria_id, $cor_id, null)) ?>" class="underline">limpar busca</a>
    </p>
<?php endif; ?>

    <!--Listagem dos Produtos-->
<?php if (count($produtos) == 0): ?>
    <p class="italic text-[#0f1e3d]/70">
        Nenhum produto encontrado com esses filtros.
        <a href="/" class="not-italic underline">Limpar filtros</a>
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
