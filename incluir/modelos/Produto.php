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
}