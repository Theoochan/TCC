<?php

class Usuario{


    public static function autenticar($email, $senha){

        $sql ='SELECT
                id,
                nome,
                email,
                senha,
                papel,
                situacao
            FROM usuario
            WHERE email = ?';

        $consulta = conexao()->prepare($sql);
        $consulta->execute([$email]);

        $usuario = $consulta->fetch();
        if ($usuario === false) {
            return null;
        }

        if ($usuario['situacao'] !== 'ativo') {
            return null;
        }

         if (password_verify($senha, $usuario['senha']) === false) {
            return null;
         }

         return $usuario;
    }


    //Cria a conta de um comprador. Devolve a lista de erros encontrados;
    //lista vazia significa que a conta foi criada (D-43).
    public static function cadastrar($dados){
        $validacao = Usuario::validarCadastro($dados);

        if (count($validacao['erros']) > 0) {
            return $validacao['erros'];
        }

        $limpos = $validacao['dados'];

        //papel e situacao ficam com o padrão do banco: 'comprador' e 'ativo'.
        //Nenhum campo do formulário consegue definir o papel.
        $sql =
            'INSERT INTO usuario
                (nome, cpf, telefone, email, senha,
                 cep, endereco, numero, complemento, cidade, uf,
                 consentimento_em, versao_termos)
            VALUES
                (?, ?, ?, ?, ?,
                 ?, ?, ?, ?, ?, ?,
                 NOW(), ?)';

        $consulta = conexao()->prepare($sql);
        $consulta->execute([
            $limpos['nome'],
            $limpos['cpf'],
            $limpos['telefone'],
            $limpos['email'],
            password_hash($limpos['senha'], PASSWORD_DEFAULT),
            $limpos['cep'],
            $limpos['endereco'],
            $limpos['numero'],
            $limpos['complemento'],
            $limpos['cidade'],
            $limpos['uf'],
            TERMOS_VERSAO,
        ]);

        return [];
    }


    //Confere e limpa os dados do formulário de cadastro, sem gravar nada.
    //Devolve 'erros' (lista vazia = tudo certo) e 'dados' (os valores limpos).
    public static function validarCadastro($dados){
        $erros = [];

        $limpos = [
            'nome'        => trim($dados['nome'] ?? ''),
            'email'       => trim($dados['email'] ?? ''),
            'cpf'         => apenasDigitos($dados['cpf'] ?? ''),
            'telefone'    => apenasDigitos($dados['telefone'] ?? ''),
            'cep'         => apenasDigitos($dados['cep'] ?? ''),
            'endereco'    => trim($dados['endereco'] ?? ''),
            'numero'      => trim($dados['numero'] ?? ''),
            'complemento' => trim($dados['complemento'] ?? ''),
            'cidade'      => trim($dados['cidade'] ?? ''),
            'uf'          => $dados['uf'] ?? '',
            //A senha não passa por trim: espaço pode ser intencional
            'senha'       => $dados['senha'] ?? '',
        ];

        $obrigatorios = [
            'nome'     => 'Informe o nome.',
            'endereco' => 'Informe o endereço.',
            'numero'   => 'Informe o número.',
            'cidade'   => 'Informe a cidade.',
        ];

        foreach ($obrigatorios as $campo => $mensagem) {
            if ($limpos[$campo] === '') {
                $erros[] = $mensagem;
            }
        }

        $erroCpf = Usuario::validarCpf($limpos['cpf']);
        if ($erroCpf !== '') {
            $erros[] = $erroCpf;
        }

        $erroEmail = Usuario::validarEmail($limpos['email']);
        if ($erroEmail !== '') {
            $erros[] = $erroEmail;
        }

        if (strlen($limpos['telefone']) !== 10 && strlen($limpos['telefone']) !== 11) {
            $erros[] = 'Informe o telefone com DDD.';
        }

        if (strlen($limpos['cep']) !== 8) {
            $erros[] = 'O CEP precisa ter 8 dígitos.';
        }

        if (! in_array($limpos['uf'], listaUfs(), true)) {
            $erros[] = 'Escolha o estado.';
        }

        //mb_strlen conta letras; strlen contaria bytes, e "ç" vale dois
        if (mb_strlen($limpos['senha']) < 8) {
            $erros[] = 'A senha precisa ter pelo menos 8 caracteres.';
        }

        if ($limpos['senha'] !== ($dados['confirmacao'] ?? '')) {
            $erros[] = 'A confirmação não confere com a senha.';
        }

        if (! isset($dados['termos'])) {
            $erros[] = 'É preciso aceitar os termos para criar a conta.';
        }

        //Complemento vazio é gravado como NULL: a coluna aceita, e "não há" é NULL
        if ($limpos['complemento'] === '') {
            $limpos['complemento'] = null;
        }

        return [
            'erros' => $erros,
            'dados' => $limpos,
        ];
    }


    //Devolve a mensagem de erro do CPF, ou texto vazio se ele for válido e
    //ainda não tiver conta. Recebe só dígitos: a limpeza acontece antes.
    public static function validarCpf($cpf){
        if (strlen($cpf) !== 11) {
            return 'O CPF precisa ter 11 dígitos.';
        }

        //Dígitos todos iguais passam na conta, mas não são CPF
        if ($cpf === str_repeat($cpf[0], 11)) {
            return 'CPF inválido.';
        }

        //Primeira volta confere o 10º dígito usando os 9 anteriores;
        //a segunda confere o 11º usando os 10 anteriores
        for ($tamanho = 9; $tamanho <= 10; $tamanho++) {
            $soma = 0;

            for ($i = 0; $i < $tamanho; $i++) {
                $soma = $soma + (int) $cpf[$i] * ($tamanho + 1 - $i);
            }

            $digito = ($soma * 10) % 11;
            if ($digito === 10) {
                $digito = 0;
            }

            if ($digito !== (int) $cpf[$tamanho]) {
                return 'CPF inválido.';
            }
        }

        $sql = 'SELECT id FROM usuario WHERE cpf = ?';

        $consulta = conexao()->prepare($sql);
        $consulta->execute([$cpf]);

        if ($consulta->fetch() !== false) {
            return 'Este CPF já está cadastrado.';
        }

        return '';
    }


    //Devolve a mensagem de erro do e-mail, ou texto vazio se ele for válido
    //e ainda não tiver conta.
    public static function validarEmail($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return 'Informe um e-mail válido.';
        }

        $sql = 'SELECT id FROM usuario WHERE email = ?';

        $consulta = conexao()->prepare($sql);
        $consulta->execute([$email]);

        if ($consulta->fetch() !== false) {
            return 'Este e-mail já está cadastrado.';
        }

        return '';
    }
}