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
}