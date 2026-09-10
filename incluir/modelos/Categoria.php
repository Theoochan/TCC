<?php

class Categoria{

    //todas as categorias em ordem alfabética
    public static function listar(){
        $sql =
            'SELECT id, nome, descricao
            FROM categoria 
            ORDER BY nome';

        return conexao()->query($sql)->fetchAll();
    }
    public static function buscar($id){
        $sql =
            'SELECT id, nome, descricao
            FROM categoria 
            WHERE id = ?';
        $consulta = conexao()->prepare($sql);
        $consulta->execute([$id]);

        $busca = $consulta->fetch();

        if($busca === false){
            return null;
        }
        return $busca;
    }
}