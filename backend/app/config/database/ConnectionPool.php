<?php

namespace app\config\database;

use \PDO;
use PDOException;

class ConnectionPool
{
    private $connections = [];
    private $maxConnections = 30;
    private $dsn;
    private $username;
    private $password;

    private $options = [
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT => true
    ];

    public function __construct()
    {
        $this->dsn = "mysql:host=db;dbname=lucasluz_test_assignment";
        $this->username = "root";
        $this->password = "lucasluz_test_assignment";
    }

    public function getConnection()
    {
        try {
            if (count($this->connections) < $this->maxConnections) {
                $connection = new PDO($this->dsn, $this->username, $this->password, $this->options);
                $this->connections[] = $connection;
                return $connection;
            } else {
                return array_shift($this->connections);
            }
        } catch (PDOException $e) {
            echo json_encode(['message' => "Error: " . $e->getMessage()]);
            die();
        }
    }

    public function releaseConnection(PDO $connection)
    {
        $this->connections[] = $connection;
    }

    public function __destruct()
    {
        foreach ($this->connections as $connection) {
            $connection = null;
        }
    }
}
