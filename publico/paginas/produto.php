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

//cores disponíveis no produto selecionado
$coresDisponiveis = Produto::coresDisponiveis($id);


//carrega a cor passada pela url e suas variantes (tamanhos)
$cor = null;
$variantes = [];

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

    //puxa as variantes (tamanhos) da cor selecionada
    $variantes = ProdutoCor::tamanhosDisponiveis($id,$cor['id']);
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
    <!-- Galeria-->
    <div class="aspect-[3/4] bg-[#e8dcc0]"></div>

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

        <!--Tamanho-->
        <div class="mb-8">
            <p class="rotulo text-xs uppercase tracking-[0.14em] mb-3">Tamanho</p>
            
            <div class="flex flex-wrap gap-2">
                <?php foreach($variantes as $item): ?>
                    <?php
                    $disponivel = VarianteProduto::disponivel($item);

                    if ($disponivel) {
                        $estilo = 'border-[#0f1e3d] hover:bg-[#0f1e3d] hover:text-[#f4ecd8]';
                        $atributo = '';
                    }else{
                        $estilo = 'border-[#0f1e3d]/25 text-[#0f1e3d]/35 line-through cursor-not-allowed';
                        $atributo = 'disabled';
                    }
                    ?>
                    <button type="button" 
                            <?= $atributo ?>
                            class="rotulo min-w-[3.25rem] px-3 py-2 border <?= $estilo ?>">
                        <?= escapar($item['tamanho']) ?>
                    </button>
                <?php endforeach ?>
            </div>
        </div>

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

<?php
require __DIR__ . '/../../incluir/rodape.php';
