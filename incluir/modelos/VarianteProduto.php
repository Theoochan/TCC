<?php

class VarianteProduto{

    public static function disponivel($variante) {
        
        return $variante['qtd_estoque'] > 0;
    }

    

}