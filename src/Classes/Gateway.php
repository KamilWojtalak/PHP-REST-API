<?php

declare(strict_types=1);

namespace App\Src\Classes;

use App\Src\Interfaces\GatewayInterface;

abstract class Gateway implements GatewayInterface
{
    protected $_dbc;
    private $_db_table;

    public function __construct($dbc)
    {
        /** Database connection */
        $this->_dbc = $dbc;
    }
}