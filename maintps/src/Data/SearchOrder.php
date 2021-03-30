<?php

namespace App\Data;

class SearchOrder
{
    /**
     * Le numéro de la commande
     *
     * @var int
     */
    public $numero;

    /**
     * Numéro de compte
     *
     * @var Account[]
     */
    public $account = [];
    
}