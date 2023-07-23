<?php

namespace Builov\MashaBot;

use mysqli;
use mysqli_result;

class Db
{
    private $conn;
    private $database = DB_BASE;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $host = DB_HOST;
    private $port = DB_PORT;
    private $charset = DB_CHARSET;


    function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die('Connect Error (' . $this->conn->connect_errno . ') ' . $this->conn->connect_error);
        }
    }

    public function execute($sql): mysqli_result|bool
    {
        return mysqli_query($this->conn, $sql);
    }

    public function close(): void
    {
        $this->conn->close();
    }
}