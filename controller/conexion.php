<?php

class Conexion
{

    private $connection;
    private $host;
    private $username;
    private $password;
    private $db;
    private $port;

    public function __construct()
    {
        $this->port = 3306;
        $this->connection = null;
        $this->host = "localhost:" . $this->port;
        $this->username = "root";
        $this->password = "M4rc3l1t4.123.abcD";
        $this->db = "seguimiento_estudiante";
    }

    public function getConnection()
    {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
        mysqli_set_charset($this->connection, "utf8");
        if (!$this->connection) {
            echo "<p style='color: red'>Error: No se pudo conectar a la Base de Datos." . PHP_EOL . "</p>";
            echo "<p style='color: red'>Error Codigo: " . mysqli_connect_errno() . PHP_EOL . "</p>";
            echo "<p style='color: red'>Error Mensaje: " . mysqli_connect_error() . PHP_EOL . "</p>";
        }
        return $this->connection;
    }

    public function closeConnection()
    {
        mysqli_close($this->connection);
    }
    public function querySelect($sql)
    {
        return $this->getConnection()->query($sql);
    }
    public function queryInsert($sql)
    {
        return $this->getConnection()->query($sql);
    }
    public function queryUpdate($sql)
    {
        return $this->getConnection()->query($sql);
    }
    public function queryDelete($sql)
    {
        return $this->getConnection()->query($sql);
    }
}
