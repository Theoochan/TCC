<?php
class Cor{

    public static function listar(){

        $sql = 
            'SELECT 
                id,
                nome,
                hex,
                hex_secundario
            FROM cor
            ORDER BY nome';
        
        return conexao()->query($sql)->fetchAll();
    }

    //retorna estilo bicolor ou monocolor para a listagem de cores
    public static function amostra($cor)
{
    if ($cor['hex_secundario'] !== null) {
        return 'linear-gradient(135deg, '
             . $cor['hex'] . ' 50%, '
             . $cor['hex_secundario'] . ' 50%)';
    }

    return $cor['hex'];
}
}