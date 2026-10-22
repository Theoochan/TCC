<?php
//url
$id = $_GET['id'] ?? null;
$cor_pedida = $_GET['cor'] ?? null;

$produto = Produto::buscar($id);

//sem Produto sem página -> 404
if ($produto === null) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}
$titulo = $produto['nome'];
$foto_pedida = $_GET['foto'] ?? null;

//Visitante sem login: guarda esta página para voltar a ela depois de entrar
if (! estaLogado()) {
    lembrarPagina();
}

//cores disponíveis no produto selecionado
$coresDisponiveis = Produto::coresDisponiveis($id);

//produtos de mesma categoria
$mesmaCategoria = Produto::listar(['categoria' => $produto['categoria_id']]);

//carrega a cor passada pela url e suas variantes (tamanhos)
$cor = null;
$variantes = [];
$galeria = [];

if (count($coresDisponiveis) > 0){

    //carrega a cor da url na variável $cor
    foreach ($coresDisponiveis as $item){
        if ($item['id'] == $cor_pedida) {
            $cor = $item;
        }
    }

    // se não foi passada na url ou não existe essa cor carrega a primeira cor
    if ($cor === null) {
        $cor = $coresDisponiveis[0];
    }

    $variantes = ProdutoCor::tamanhosDisponiveis($id,$cor['id']);

    $galeria = ProdutoCor::galeria($id, $cor['id']);
}

$foto = null;

foreach ($galeria as $item){
    if ($item['ordem'] == $foto_pedida){
        $foto = $item;
    }
}

if ($foto === null && count($galeria) > 0){
    $foto = $galeria[0];
}

require __DIR__ . '/../../incluir/topo.php';
?>

<nav class="rotulo text-[11px] uppercase tracking-[0.2em] text-[#0f1e3d]/60 mb-8">
    <a href="/" class="hover:underline">Loja</a>
    <span class="mx-2">/</span>
    <a href="/?categoria=<?= escapar($produto['categoria_id']) ?>" class="hover:underline">
        <?= escapar($produto['categoria_nome']) ?>
    </a>
</nav>
<div class="grid gap-10 md:grid-cols-2 md:gap-14">
    <!-- Galeria -->
    <div>
        <?php if ($foto !== null): ?>
            <img src="<?= escapar(urlImagem($foto['arquivo'])) ?>"
                alt="<?= escapar($produto['nome'] . ' — ' . $cor['nome'] . ', foto ' . $foto['ordem'] . ' de ' . count($galeria)) ?>"
                class="w-full aspect-[3/4] object-cover">
        <?php else: ?>
            <img src="<?= escapar(urlImagem(null)) ?>"
                alt="Imagem indisponível"
                class="w-full aspect-[3/4] object-cover">
        <?php endif; ?>

        <?php if (count($galeria) > 1): ?>
            <div class="flex gap-2 mt-3">
                <?php foreach ($galeria as $item): ?>
                    <a href="/produto?id=<?= escapar($produto['id']) ?>&amp;cor=<?= escapar($cor['id']) ?>&amp;foto=<?= escapar($item['ordem']) ?>"
                    class="w-16 border-2 <?= $item['ordem'] == $foto['ordem'] ? 'border-[#a63a2a]' : 'border-transparent' ?>">
                        <img src="<?= escapar(urlImagem($item['arquivo'])) ?>"
                            alt="Ver foto <?= escapar($item['ordem']) ?>"
                            class="w-full aspect-[3/4] object-cover">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <!--Ficha do produto-->
    <div>
        <p class="rotulo text-[11px] uppercase tracking-[0.3em] text-[#a63a2a] mb-3">
            <?= escapar($produto['categoria_nome']); ?>
        </p>

        <h1 class="titulo text-3xl md:text-4xl font-black leading-tight mb-4">
            <?= escapar($produto['nome']); ?>
        </h1>

        <p class="rotulo text-2xl mb-6">
            <?= escapar(dinheiro($produto['valor'])); ?>
        </p>

        <p class="mb-8 text-[#0f1e3d]/80">
            <?= escapar($produto['descricao']); ?>
            
        </p>
        

        <!--Amostras de coresDisponiveis-->
        <div class="mb-7">
            <!--Amostra selecionada-->
            <p class="rotulo text-xs uppercase tracking-[0.14em] mb-3">
                Cor . <?= escapar($cor['nome']) ?>
            </p>

            <!--Listagem de Amostras-->
            <div class="flex gap-3">
                <?php foreach($coresDisponiveis as $item): ?>
                    <a  href="/produto?id=<?= escapar($produto['id']) ?>&amp;cor=<?= escapar($item['id']) ?>"
                        title="<?= escapar($item['nome']) ?>"
                        class="w-9 h-9 rounded-full border border-[#0f1e3d]/30 <?= $item['id'] == $cor['id'] ? 'ring-2 ring-offset-2 ring-[#a63a2a]' : '' ?>"
                        style="background: <?= escapar(Cor::amostra($item)) ?>"
                    ></a>
                <?php endforeach; ?>
            </div>

        </div>

        <!--Tamanho e sacola-->
        <form method="post" action="/sacola/adicionar" class="mb-8">
            <input type="hidden" name="token" value="<?= escapar(tokenCsrf()) ?>">
            <input type="hidden" name="produto_id" value="<?= escapar($produto['id']) ?>">
            <input type="hidden" name="cor_id" value="<?= escapar($cor['id']) ?>">

            <p class="rotulo text-xs uppercase tracking-[0.14em] mb-3">Tamanho</p>

            <!-- Cada tamanho é um rádio escondido (sr-only) e um <span> com cara
                 de botão. peer marca o rádio; peer-checked: pinta o <span> quando
                 ele está escolhido e peer-disabled: risca o esgotado (D-11).
                 Tudo em CSS, sem JavaScript. -->
            <div class="flex flex-wrap gap-2 mb-6">
                <?php foreach ($variantes as $item): ?>
                    <label>
                        <input type="radio" name="variante_id" required
                               value="<?= escapar($item['id']) ?>"
                               class="peer sr-only"
                               <?= VarianteProduto::disponivel($item) ? '' : 'disabled' ?>>
                        <span class="rotulo inline-block min-w-[3.25rem] px-3 py-2 border border-[#0f1e3d] text-center cursor-pointer
                                     peer-checked:bg-[#0f1e3d] peer-checked:text-[#f4ecd8]
                                     peer-focus-visible:ring-2 peer-focus-visible:ring-[#a63a2a]
                                     peer-disabled:border-[#0f1e3d]/25 peer-disabled:text-[#0f1e3d]/35
                                     peer-disabled:line-through peer-disabled:cursor-not-allowed">
                            <?= escapar($item['tamanho']) ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>

            <?php if (estaLogado()): ?>
                <label class="flex items-center gap-3 mb-6">
                    <span class="rotulo text-xs uppercase tracking-[0.14em]">Quantidade</span>
                    <input type="number" name="qtde" value="1" min="1" required
                           class="w-20 py-2 bg-transparent border-b border-[#0f1e3d] text-center focus:outline-none focus:border-[#a63a2a]">
                </label>

                <button type="submit"
                        class="rotulo uppercase tracking-[0.16em] bg-[#0f1e3d] text-[#f4ecd8] px-6 py-4 w-full cursor-pointer hover:bg-[#a63a2a]">
                    Adicionar à sacola
                </button>
            <?php else: ?>
                <a href="/entrar"
                   class="rotulo block text-center uppercase tracking-[0.16em] border border-[#0f1e3d] px-6 py-4 hover:bg-[#0f1e3d] hover:text-[#f4ecd8]">
                    Entre para comprar
                </a>
            <?php endif; ?>
        </form>

        <!--Atributos nullable-->       
        <div class="border-t border-[#0f1e3d]/20">
            <?php if($produto['composicao']!== null): ?>
                <div class="border-b border-[#0f1e3d]/20 py-4">
                    <h2 class="rotulo text-xs uppercase tracking-[0.14em] mb-1">
                        Composição &amp; fit
                    </h2>
                    <p class="text-sm text-[#0f1e3d]/80">
                        <?= escapar($produto['composicao']) ?>
                        <?php if($produto['modelagem'] !== null):  ?>
                            Modelagem: <?= escapar($produto['modelagem']); ?>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if($produto['cuidados']!== null): ?>
                <div class="border-b border-[#0f1e3d]/20 py-4">
                    <h2 class="rotulo text-xs uppercase tracking-[0.14em] mb-1">Cuidados</h2>
                    <p class="text-sm text-[#0f1e3d]/80">
                        <?= escapar($produto['cuidados']); ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if($produto['envio_devolucao']!== null): ?>
                <div class="border-b border-[#0f1e3d]/20 py-4">
                    <h2 class="rotulo text-xs uppercase tracking-[0.14em] mb-1">Envio &amp; Devolução</h2>
                    <p class="text-sm text-[#0f1e3d]/80">
                        <?= escapar($produto['envio_devolucao']); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
</div>

<?php if(count($mesmaCategoria) > 1): ?>
    <section class="mt-20 border-t border-[#0f1e3d]/20 pt-10">
        <h2 class="titulo text-2xl md:text-3xl font-black mb-8">
            Mais em <?= escapar($produto['categoria_nome']) ?>
        </h2>
        <ul class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach($mesmaCategoria as $item):?>
                <li>
                    <a href="/produto?id=<?= escapar($item['id']) ?>" class="block group">
                        <div class="aspect-[3/4] bg-[#e8dcc0] mb-3"></div>
                        <h3 class="titulo text-lg font-bold group-hover:text-[#a63a2a]">
                            <?= escapar($item['nome']); ?>
                        </h3>
                        <p class="rotulo text-sm mt-1">
                            <?= escapar(dinheiro($item['valor'])); ?>
                        </p>
                    </a>
                    
                </li>
                
            <?php endforeach?>
        </ul>
    </section>
<?php endif; ?>

<?php
require __DIR__ . '/../../incluir/rodape.php';
