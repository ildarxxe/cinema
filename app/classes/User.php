<?php 
namespace App\classes;

class User implements Model {
    private object $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($table_name, $data): bool|string
    {
        $keys = array_keys($data);
        $values = array_values($data);

        $keys_string = implode(", ", $keys);
        $placeholders = array_fill(0, count($values), "?");
        $values_string = implode(", ", $placeholders);

        $sql = "INSERT INTO $table_name ($keys_string) VALUES ($values_string)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return true;
    }

    public function update() {

    }
    public function delete() {

    }
}