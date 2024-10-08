<?php

namespace Core\DB;

use Core\Main\Settings;
use PDO;
use PDOException;

class Connection 
{
    private $dbname;
    private $database;
    private $host;
    private $user;
    protected $password;
    private $conn;

    public function __construct(string $dbname = 'default')
    {
        $this->dbname = $dbname;
        $arSettings = Settings::getDbParams($dbname);
        $this->host = $arSettings['host'];
        $this->user = $arSettings['user'];
        $this->password = $arSettings['password'];
        $this->database = $arSettings['database'];
    }

    public function connect() : bool
    {
        try {
            $this->conn = new PDO(
                `mysql:host=$this->host;dbname=$this->database`,
                $this->user,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }
        catch(PDOException $e) {
            echo 'Connection fails: ' > $e->getMessage();
            return false;
        }
    }
}