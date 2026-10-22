<?php

class VarianteProduto{

    public static function buscar($id){
        $sql =
            'SELECT
                id,
                produto_id,
                cor_id,
                tamanho,
                qtd_estoque,
                situacao
            FROM variante_produto
            WHERE id = ?';

        $consulta = conexao()->prepare($sql);
        $consulta->execute([$id]);

        $linha = $consulta->fetch();
        if ($linha === false) {
            return null;
        }

        return $linha;
    }

    //Quantas unidades podem ser vendidas agora. Hoje é o estoque inteiro;
    //na E5 passa a descontar as reservas vigentes (regra 5 da seção 2.4.1),
    //e só este método muda.
    public static function quantidadeDisponivel($variante){
        return (int) $variante['qtd_estoque'];
    }

    public static function disponivel($variante){
        return VarianteProduto::quantidadeDisponivel($variante) > 0;
    }
}
