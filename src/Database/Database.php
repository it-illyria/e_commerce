<?php

namespace App\Database;

use mysqli;

class Database
{
    private string $hostname = 'localhost';
    private string $username = 'root';
    private string $password = '';
    private string $database = 'store_backend';

    private $connection;

    public function getConnection()
    {
        // Check if the connection exists
        if (!$this->connection) {
            $this->connection = new mysqli($this->hostname, $this->username, $this->password, $this->database);
            if ($this->connection->connect_error) {
                die('Connection failed: ' . $this->connection->connect_error);
            }
        }

        return $this->connection;
    }
}
