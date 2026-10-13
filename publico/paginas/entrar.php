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

<h1 class="titulo text-4xl font-black mb-7">Entrar</h1>

<?php if ($erro !== ''): ?>
    <p class="bg-[#e8dcc0] border-l-[3px] border-[#a63a2a] px-4 py-3 mb-6">
        <?= escapar($erro) ?>
    </p>
<?php endif; ?>

<form method="post" action="/entrar" class="max-w-sm">

    <label class="rotulo block text-xs uppercase tracking-[0.14em] mb-1">E-mail</label>
    <input type="email" name="email" required autofocus
           value="<?= escapar($email) ?>"
           class="w-full border border-[#0f1e3d]/30 px-3 py-2 mb-5 bg-transparent">

    <label class="rotulo block text-xs uppercase tracking-[0.14em] mb-1">Senha</label>
    <input type="password" name="senha" required
           class="w-full border border-[#0f1e3d]/30 px-3 py-2 mb-7 bg-transparent">

    <button type="submit"
            class="rotulo uppercase tracking-[0.16em] bg-[#0f1e3d] text-[#f4ecd8] px-6 py-3">
        Entrar
    </button>
</form>

<?php
require __DIR__ . '/../../incluir/rodape.php';