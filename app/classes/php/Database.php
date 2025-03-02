<?php 

namespace App\classes\php;
use PDO;
use PDOException;

class Database { 

    private $pdo;

    public function __construct($host='localhost', $dbname = 'grand_cinema', $user = 'root', $password = 'admin') {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname",$user,$password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getPDO() {
        return $this->pdo;
    }
}