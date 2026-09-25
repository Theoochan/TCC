<?php

class Produto{
    public static function listar($categoria_id = null){
        $sql =
            'SELECT id, nome, valor
            FROM produto';
        
        $valores = [];

        if($categoria_id !== null){
            $sql .= ' WHERE categoria_id = ?';
            $valores[] = $categoria_id;
        }

        $sql .= ' ORDER BY nome';

        $consulta = conexao()->prepare($sql);
        $consulta->execute($valores);
        return $consulta->fetchAll();
    }
    public static function buscar($id){
        $sql =
            'SELECT 
                p.id,
                p.categoria_id,
                p.nome,
                p.valor,
                p.descricao,
                p.modelagem,
                p.composicao,
                p.cuidados,
                p.envio_devolucao,
                c.nome as categoria_nome
            FROM produto p
            JOIN categoria as c on c.id = p.categoria_id
            WHERE p.id = ?';

        $consulta = conexao()->prepare($sql);
        $consulta->execute([$id]);
        
        $linha = $consulta->fetch();
        if ($linha === false) {
            return null;
        }

        return $linha;
        
    }

    //Cores disponíveis em um produto
    public static function coresDisponiveis($id){
        $sql = 
            'SELECT
                c.id,
                c.nome,
                c.hex,
                c.hex_secundario
            FROM produto_cor pc
            JOIN cor c on c.id = pc.cor_id
            WHERE pc.produto_id = ?
            ORDER BY c.nome';

        $consulta = conexao()->prepare($sql);
        $consulta->execute([$id]);

        return $consulta->fetchAll();
        
    }
}