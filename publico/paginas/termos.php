<?php

//Termos de uso e política de privacidade, na versão que o cadastro grava
//em usuario.versao_termos (D-20). O texto definitivo é a pendência DOC-08.

$titulo = 'Termos de uso';

require __DIR__ . '/../../incluir/topo.php';
?>

<h1 class="titulo text-4xl md:text-5xl font-black mb-3">Termos de uso e privacidade</h1>
<p class="italic text-sm text-[#0f1e3d]/65 mb-10">Versão <?= escapar(TERMOS_VERSAO) ?></p>

<!-- Aviso de rascunho: sai quando o texto definitivo entrar (DOC-08) -->
<div class="bg-[#e8dcc0] border-l-[3px] border-[#a63a2a] px-4 py-3">
    <p class="rotulo text-xs uppercase tracking-[0.16em] text-[#a63a2a] mb-2">Rascunho</p>
    <p>
        O texto destes termos está em elaboração. Ele vai descrever quais dados
        pessoais a Wonner coleta, para que são usados, por quanto tempo ficam
        guardados e como pedir a eliminação, conforme a Lei Geral de Proteção de
        Dados (Lei nº 13.709/2018).
    </p>
</div>

<?php
require __DIR__ . '/../../incluir/rodape.php';
