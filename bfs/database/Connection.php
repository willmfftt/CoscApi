<?php

namespace Bfs\Database;

use PDO;
use PDOException;

/**
 * Provides single class for database connection
 *
 * @author William Moffitt
 */
class Connection {
    
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            echo 'Error connecting to database: ' . $e->getMessage();
            die();
        }
    }
    
    public function getPdo() {
        return $this->pdo;
    }
    
}
