<?php

// ────────────────────────────────────────────────────────────────
// Funções curtas usadas em várias páginas.
//
// Regra para entrar aqui: é usada em mais de uma página E não tem
// a ver com nenhuma tabela específica. Se tem a ver com uma tabela,
// vai no modelo dela.
// ────────────────────────────────────────────────────────────────


// Escapa texto antes de imprimir no HTML.
//
// Use em TODO valor que vem do banco ou do usuário. Sem isso, um
// nome de produto contendo <script> seria executado pelo navegador
// de quem visita a loja.
function escapar($texto)
{
    if ($texto === null) {
        return '';
    }
    
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}


// 189.9 → "R$ 189,90"
function dinheiro($valor)
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}


// 2026-08-25 14:32:00 → "25/08/2026 14:32"
function dataHora($valor)
{
    if (empty($valor)) {
        return '';
    }

    return date('d/m/Y H:i', strtotime($valor));
}


// Manda o navegador para outro endereço e para a execução.
//
// O exit é obrigatório: header() sozinho não interrompe nada, e o
// código continuaria rodando depois do redirecionamento.
function redirecionar($url)
{
    header('Location: ' . $url);
    exit;
}


function estaLogado()
{
    return isset($_SESSION['usuario_id']);
}


function ehAdmin()
{
    return isset($_SESSION['papel']) && $_SESSION['papel'] == 'admin';
}


// Guarda um aviso para mostrar na próxima página.
// Usado depois de salvar algo e redirecionar.
function avisar($mensagem)
{
    $_SESSION['aviso'] = $mensagem;
}


// Lê o aviso guardado e o apaga, para não aparecer duas vezes.
function pegarAviso()
{
    if (! isset($_SESSION['aviso'])) {
        return null;
    }

    $aviso = $_SESSION['aviso'];
    unset($_SESSION['aviso']);

    return $aviso;
}

//Endereço de imagem, se não existe ou recebe null, retorna imagem genérica
function urlImagem($arquivo) {
    if ($arquivo === null || $arquivo === '') {
        return '/img/sem-imagem.jpg';
    }

    if (! file_exists(__DIR__ . '/../publico/uploads/' . $arquivo)) {
        return '/img/sem-imagem.jpg';
    }

    return '/uploads/' . $arquivo;
}

function urlVitrine($categoria_id, $cor_id, $busca){
    
    $parametros = http_build_query([
        'categoria' => $categoria_id,
        'cor' => $cor_id,
        'busca' => $busca
    ]);

    if ($parametros === '') {
        return '/';
    }

    return '/?' . $parametros;
}


//Número secreto desta sessão, usado para provar que o formulário saiu de
//uma página nossa. É criado na primeira vez que um formulário é desenhado
//e reaproveitado depois — inclusive quando o formulário é redesenhado com erro.
function tokenCsrf(){
    if (! isset($_SESSION['token_csrf'])) {
        $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['token_csrf'];
}


//Confere o token que veio no formulário contra o guardado na sessão.
//hash_equals compara em tempo constante: não responde mais rápido
//quando o erro está no começo, e por isso não vaza o token por medição.
function tokenValido(){
    if (! isset($_SESSION['token_csrf'])) {
        return false;
    }

    if (! isset($_POST['token'])) {
        return false;
    }

    return hash_equals($_SESSION['token_csrf'], $_POST['token']);
}


//Guarda a página atual, para voltar a ela depois do login.
//Só é chamada em rotas que nós escolhemos, então o valor sempre começa
//pelo caminho delas — /produto, /sacola, /admin — e nunca aponta para fora.
//Por estar na sessão, e não na URL, ninguém de fora consegue escrevê-lo.
function lembrarPagina(){
    $_SESSION['voltar'] = $_SERVER['REQUEST_URI'];
}


//Lê a página guardada e a apaga, para não ser usada duas vezes — o mesmo
//desenho do avisar() e do pegarAviso(). Sem página guardada, devolve a inicial.
function paginaLembrada(){
    $pagina = $_SESSION['voltar'] ?? '/';
    unset($_SESSION['voltar']);

    return $pagina;
}


//Deixa só os dígitos: "123.456.789-09" vira "12345678909".
//O padrão /[^0-9]/ quer dizer "qualquer caractere que NÃO seja de 0 a 9";
//cada um que casar é trocado por nada.
function apenasDigitos($texto){
    return preg_replace('/[^0-9]/', '', $texto);
}


//As 27 unidades da federação. A página de cadastro desenha o <select> com
//esta lista, e o modelo confere o que chegou contra ela: o navegador pode
//enviar qualquer valor, inclusive um que o <select> não oferecia.
//Fica aqui, e não num modelo, porque venda também tem uf (E5).
function listaUfs(){
    return [
        'AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO',
        'MA', 'MG', 'MS', 'MT', 'PA', 'PB', 'PE', 'PI', 'PR',
        'RJ', 'RN', 'RO', 'RR', 'RS', 'SC', 'SE', 'SP', 'TO',
    ];
}