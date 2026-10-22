<?php

class CarrinhoItem{

    //Põe uma variante na sacola. Se ela já estiver lá, soma a quantidade
    //em vez de criar outra linha. Devolve a lista de erros; vazia = incluída (D-43).
    public static function incluir($usuario_id, $variante_id, $qtde){
        if ($variante_id === '') {
            return ['Escolha um tamanho.'];
        }

        $qtde = (int) $qtde;
        if ($qtde < 1) {
            return ['Escolha uma quantidade.'];
        }

        $variante = VarianteProduto::buscar($variante_id);
        if ($variante === null || $variante['situacao'] !== 'ativo') {
            return ['Este tamanho não está disponível.'];
        }

        //Quanto desta variante já está na sacola
        $sql = 'SELECT qtde FROM carrinho_item WHERE usuario_id = ? AND variante_produto_id = ?';

        $consulta = conexao()->prepare($sql);
        $consulta->execute([$usuario_id, $variante_id]);

        $linha    = $consulta->fetch();
        $naSacola = 0;
        if ($linha !== false) {
            $naSacola = (int) $linha['qtde'];
        }

        //O limite vale para o total, não só para o que está sendo acrescentado
        $total      = $naSacola + $qtde;
        $disponivel = VarianteProduto::quantidadeDisponivel($variante);

        if ($total > $disponivel) {
            $mensagem = 'Só há ' . $disponivel . ' unidade(s) deste tamanho.';

            if ($naSacola > 0) {
                $mensagem = $mensagem . ' Você já tem ' . $naSacola . ' na sacola.';
            }

            return [$mensagem];
        }

        //Primeira vez: cria a linha. Já estava: atualiza a quantidade.
        if ($naSacola === 0) {
            $sql     = 'INSERT INTO carrinho_item (usuario_id, variante_produto_id, qtde) VALUES (?, ?, ?)';
            $valores = [$usuario_id, $variante_id, $total];
        } else {
            $sql     = 'UPDATE carrinho_item SET qtde = ? WHERE usuario_id = ? AND variante_produto_id = ?';
            $valores = [$total, $usuario_id, $variante_id];
        }

        $consulta = conexao()->prepare($sql);
        $consulta->execute($valores);

        return [];
    }
}
