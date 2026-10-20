<?php

//Tela de entrada: mostra o formulário e recebe o envio dele.

$titulo = 'Entrar';
$erro   = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email   = trim($_POST['email']);
    $usuario = Usuario::autenticar($email, $_POST['senha']);

    if ($usuario !== null) {
        //Troca o identificador da sessão no login. Se alguém tiver descoberto
        //o identificador anterior, ele deixa de valer exatamente agora.
        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nome']       = $usuario['nome'];
        $_SESSION['papel']      = $usuario['papel'];

        redirecionar('/');
    }

    $erro = 'E-mail ou senha inválidos.';
}

require __DIR__ . '/../../incluir/topo.php';
?>

<!-- Mesmo estilo de campo do cadastro, que segue docs/design/Checkout.dc.html -->
<div class="max-w-sm mx-auto">
    <h1 class="titulo text-4xl md:text-5xl font-black mb-10 text-center">Entrar</h1>

    <?php if ($erro !== ''): ?>
        <p class="bg-[#e8dcc0] border-l-[3px] border-[#a63a2a] px-4 py-3 mb-8">
            <?= escapar($erro) ?>
        </p>
    <?php endif; ?>

    <form method="post" action="/entrar" class="flex flex-col gap-8">
        <input type="hidden" name="token" value="<?= escapar(tokenCsrf()) ?>">

        <label class="flex flex-col gap-2">
            <span class="italic text-xs text-[#0f1e3d]/55">E-mail</span>
            <input type="email" name="email" required autofocus
                   value="<?= escapar($email) ?>"
                   class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
        </label>

        <label class="flex flex-col gap-2">
            <span class="italic text-xs text-[#0f1e3d]/55">Senha</span>
            <input type="password" name="senha" required
                   class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
        </label>

        <button type="submit"
                class="rotulo uppercase tracking-[0.16em] bg-[#0f1e3d] text-[#f4ecd8] px-6 py-4 w-full cursor-pointer hover:bg-[#a63a2a]">
            Entrar
        </button>
    </form>

    <p class="mt-8 pt-6 border-t border-[#0f1e3d]/20 italic text-sm text-[#0f1e3d]/65 text-center">
        Ainda não tem conta?
        <a href="/cadastro" class="not-italic text-[#0f1e3d] underline underline-offset-4">Criar conta</a>
    </p>
</div>

<?php
require __DIR__ . '/../../incluir/rodape.php';