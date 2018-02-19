<?php

class ConectDB
{
    private $port;
    private $host;
    private $user;
    private $pass;
    private $dbName;

    private $connection;

    function __construct($host, $port, $user, $pass, $dbName)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbName = $dbName;
    }

    public function ConnectionDB()
    {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->dbName);

        if ($this->connection->connect_error) {
            echo "connection error" . $this->connection->connect_error;
            die();
        }
        echo "Connection successfully";
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function ConnectionClose()
    {
        $this->connection->close();
    }


}