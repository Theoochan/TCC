<?php

//Tela de cadastro: mostra o formulário e recebe o envio dele.
//Toda a validação está em Usuario::cadastrar() (D-43).

$titulo = 'Criar conta';
$erros  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erros = Usuario::cadastrar($_POST);

    if (count($erros) === 0) {
        avisar('Conta criada. Entre com seu e-mail e senha.');
        redirecionar('/entrar');
    }
}

require __DIR__ . '/../../incluir/topo.php';
?>

<h1 class="titulo text-4xl md:text-5xl font-black mb-10">Criar conta</h1>

<?php if (count($erros) > 0): ?>
    <div class="bg-[#e8dcc0] border-l-[3px] border-[#a63a2a] px-4 py-3 mb-10">
        <p class="mb-2">Corrija para continuar:</p>
        <ul class="list-disc pl-5">
            <?php foreach ($erros as $erro): ?>
                <li><?= escapar($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Estilo dos campos segue docs/design/Checkout.dc.html: rótulo em itálico
     acima, campo só sublinhado. Os campos são repreenchidos com o que foi
     enviado; senha e confirmação, não. -->
<form method="post" action="/cadastro" class="flex flex-col gap-16">
    <input type="hidden" name="token" value="<?= escapar(tokenCsrf()) ?>">

    <section>
        <div class="flex justify-between items-baseline mb-7">
            <h2 class="titulo text-3xl font-bold">Seus dados</h2>
            <a href="/entrar" class="italic text-sm text-[#0f1e3d]/65 underline underline-offset-4">
                já tenho conta
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
            <label class="flex flex-col gap-2 md:col-span-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Nome completo</span>
                <input type="text" name="nome" required
                       value="<?= escapar($_POST['nome'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
            </label>

            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">CPF</span>
                <input type="text" name="cpf" required inputmode="numeric"
                       placeholder="000.000.000-00"
                       value="<?= escapar($_POST['cpf'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] placeholder:text-[#0f1e3d]/30 focus:outline-none focus:border-[#a63a2a]">
            </label>

            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Telefone</span>
                <input type="tel" name="telefone" required
                       placeholder="(00) 00000-0000"
                       value="<?= escapar($_POST['telefone'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] placeholder:text-[#0f1e3d]/30 focus:outline-none focus:border-[#a63a2a]">
            </label>

            <label class="flex flex-col gap-2 md:col-span-2">
                <span class="italic text-xs text-[#0f1e3d]/55">E-mail</span>
                <input type="email" name="email" required
                       value="<?= escapar($_POST['email'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
            </label>
        </div>
    </section>

    <section>
        <h2 class="titulo text-3xl font-bold mb-7">Endereço</h2>

        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-x-6 gap-y-8 mb-8">
            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">CEP</span>
                <input type="text" name="cep" required inputmode="numeric"
                       placeholder="00000-000"
                       value="<?= escapar($_POST['cep'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] placeholder:text-[#0f1e3d]/30 focus:outline-none focus:border-[#a63a2a]">
            </label>

            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Endereço</span>
                <input type="text" name="endereco" required
                       placeholder="Rua, avenida"
                       value="<?= escapar($_POST['endereco'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] placeholder:text-[#0f1e3d]/30 focus:outline-none focus:border-[#a63a2a]">
            </label>

            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Número</span>
                <input type="text" name="numero" required
                       value="<?= escapar($_POST['numero'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
            </label>

            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Complemento (opcional)</span>
                <input type="text" name="complemento"
                       placeholder="Apartamento, bloco"
                       value="<?= escapar($_POST['complemento'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] placeholder:text-[#0f1e3d]/30 focus:outline-none focus:border-[#a63a2a]">
            </label>
        </div>

        <!-- Cidade larga e estado estreito: a ordem inversa da linha de cima -->
        <div class="grid grid-cols-1 md:grid-cols-[1fr_180px] gap-x-6 gap-y-8">
            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Cidade</span>
                <input type="text" name="cidade" required
                       value="<?= escapar($_POST['cidade'] ?? '') ?>"
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
            </label>

            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Estado</span>
                <select name="uf" required
                        class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
                    <option value="">Escolha</option>
                    <?php foreach (listaUfs() as $sigla): ?>
                        <option value="<?= escapar($sigla) ?>"
                            <?= ($_POST['uf'] ?? '') === $sigla ? 'selected' : '' ?>>
                            <?= escapar($sigla) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
    </section>

    <section>
        <h2 class="titulo text-3xl font-bold mb-7">Acesso</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Senha — mínimo de 8 caracteres</span>
                <input type="password" name="senha" required
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
            </label>

            <label class="flex flex-col gap-2">
                <span class="italic text-xs text-[#0f1e3d]/55">Confirme a senha</span>
                <input type="password" name="confirmacao" required
                       class="py-2.5 bg-transparent border-b border-[#0f1e3d] focus:outline-none focus:border-[#a63a2a]">
            </label>
        </div>
    </section>

    <div class="border-t border-[#0f1e3d]/20 pt-8 flex flex-col gap-8">
        <!-- O link abre em aba nova: na mesma aba, sairia do cadastro e
             perderia o que já foi digitado. rel="noopener" impede a aba nova
             de mexer nesta. -->
        <label class="flex gap-3 items-start">
            <input type="checkbox" name="termos" value="1" required
                   class="mt-1 accent-[#0f1e3d]"
                   <?= isset($_POST['termos']) ? 'checked' : '' ?>>
            <span>
                Li e aceito os
                <a href="/termos" target="_blank" rel="noopener"
                   class="underline underline-offset-4">termos de uso e a política de privacidade</a>.
            </span>
        </label>

        <button type="submit"
                class="rotulo uppercase tracking-[0.16em] bg-[#0f1e3d] text-[#f4ecd8] px-6 py-4 w-full md:w-auto md:self-start cursor-pointer hover:bg-[#a63a2a]">
            Criar conta
        </button>
    </div>
</form>

<?php
require __DIR__ . '/../../incluir/rodape.php';
