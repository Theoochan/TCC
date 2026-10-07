<?php

class Produto{
    public static function listar($filtros = []){
        $sql ='SELECT id, nome, valor FROM produto';
        
        $condicoes = [];
        $valores = [];

        if (isset($filtros['categoria'])){
            $condicoes[] = 'categoria_id = ?';
            $valores[] = $filtros['categoria'];
        }

        if (isset($filtros['cor'])){
            $condicoes[] = 'id IN (SELECT produto_id FROM produto_cor where cor_id = ?)';
            $valores[] = $filtros['cor'];
        }

        if (isset($filtros['busca'])){
            $condicoes[] = '(nome LIKE ? OR descricao LIKE ? OR modelagem LIKE ?)';
            $termo = '%' . $filtros['busca'] . '%';
            $valores[] = $termo;
            $valores[] = $termo;
            $valores[] = $termo;
        }

        // se há condições -> há filtro
        if(count($condicoes) > 0){
            $sql .= ' WHERE ' . implode(' AND ', $condicoes);
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