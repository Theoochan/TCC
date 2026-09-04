<?php

class Categoria{

    //todas as categorias em ordem alfabética
    public static function todas(){
        $sql = "SELECT id, nome, descricao FROM categoria ORDER BY nome";

        return conexao()->query($sql)->fetchAll();
    }
}