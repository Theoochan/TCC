<?php

class ProdutoCor{
    
    //tamanhos de um produto em uma cor, dados em variante produto e pergunta sobre produto_cor (quais tamanhos esse produto cor tem ?)
    //Se cor = null traz tamanhos em todas as cores disponíveis no produto
    public static function tamanhosDisponiveis($produto_id, $cor_id = null){
        $sql =
            'SELECT 
                v.id,
                v.cor_id,
                v.tamanho,
                v.qtd_estoque 
            FROM variante_produto v
            WHERE v.produto_id = ?
            AND v.situacao = \'ativo\'';
        
        $valores = [$produto_id];

        if ($cor_id !== null) {
            $sql .= 'AND v.cor_id = ?';
            $valores[] = $cor_id;
        }

        $sql .= 'ORDER BY v.tamanho';

        $consulta = conexao()->prepare($sql);
        $consulta->execute($valores);

        return $consulta->fetchAll();
    }

    public static function galeria($produto_id, $cor_id = null){

        $sql = 'SELECT cor_id, arquivo, ordem
                FROM    imagem_produto
                WHERE   produto_id = ?';
        
        $valores = [$produto_id];

        if ($cor_id !== null) {
            $sql .= ' AND cor_id = ?';
            $valores[] = $cor_id;
        }

        $sql .= ' ORDER BY cor_id, ordem';

        $consulta = conexao()->prepare($sql);
        $consulta->execute($valores);

        return $consulta->fetchAll();
    }
}