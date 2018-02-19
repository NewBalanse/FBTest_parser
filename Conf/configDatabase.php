<?php

class configDatabase
{
    private $port;
    private $host;
    private $userName;
    private $password;
    private $dbName;

    /**
     * @return mixed
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    function __construct()
    {
        $this->port = "localhost";
        $this->host = 3306;
        $this->userName = "root";
        $this->password = "X2yhsg3j7a9E3159";
        $this->dbName = "";
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


}