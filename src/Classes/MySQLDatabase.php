<?php

declare(strict_types=1);

namespace App\Src\Classes;

// use App\Src\Interfaces\DatabaseInterface;
use \PDO;

// class Database implements DatabaseInterface
class MySQLDatabase extends Database
{
    protected $_dbc = null;

    public function __construct(
        string $host,
        string $dbname,
        string $user,
        string $pswd
    ) {
        parent::__construct($host, $dbname, $user, $pswd);
    }

    public function getConnection()
    {
        /** If there is already set database connection return it */
        if ($this->_dbc) return $this->_dbc;

        /** Set the dsn */
        $dsn = "mysql:host={$this->_host};dbname={$this->_dbname}";

        /** PDO options */
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        /** Return PDO conn */
        $this->_dbc = new PDO($dsn, $this->_user, $this->_pswd, $options);

        return $this->_dbc;
    }
}
