<?php

declare(strict_types=1);

namespace App\Src\Classes;

use App\Src\Interfaces\DatabaseInterface;

class Database implements DatabaseInterface
{
    protected $_dbc = null;

    public function __construct(
        string $host,
        string $dbname,
        string $user,
        string $pswd
    ) {
        $this->_host = $host;
        $this->_dbname = $dbname;
        $this->_user = $user;
        $this->_pswd = $pswd;
    }

    public function getConnection()
    {
    }
}
