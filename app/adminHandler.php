<?php
session_start();
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

use App\classes\php\Database;

require_once('./classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

$sql_role = "SELECT role_id FROM users_role WHERE user_id = :id";
$sql_role = $pdo->prepare($sql_role);
$sql_role->bindParam(':id', $_SESSION['user_id']);
$sql_role->execute();
$role = $sql_role->fetch();

if ($role['role_id'] == 2) {
    if ($data['action'] === 'create') {
        $inputs = $data["inputs"] ?? null;
        $user_id_phone = null;

        foreach ($inputs as $input => &$value) {
            if ($input == "password") {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
            if ($input == "user_id") {
                $user_id_phone = $value;
            }
        }

        $phones = [];
        foreach ($inputs as $input) {
            array_push($phones, $value);
        }
        $phones = array_shift($phones);
        $keys = array_keys($inputs);
        $values = array_values($inputs);

        $keys_str = implode(", ", $keys);
        $values_str = implode(', ', array_fill(0, count($values), "?"));

        print_r($data);

        $table_name = $data["table_name"] ?? null;
        try {
            if ($table_name === "users_phone") {
                $sql_phone = "INSERT INTO $table_name (user_id, phones) VALUES (:user_id, :phone)";
                $stmt_phone = $pdo->prepare($sql_phone);
                $stmt_phone->bindParam(':user_id', $user_id_phone);
                $stmt_phone->bindParam(':phone', $phones);
                $stmt_phone->execute();
            } else {
                $sql = "INSERT INTO $table_name ($keys_str) VALUES ($values_str)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($values);
            }
            echo json_encode(["message" => 'Успешно!']);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    if ($data['action'] === 'delete') {
        $id = $data['id'] ?? null;
        $column = $data['column'] ?? null;
        $table_name = $data["table_name"] ?? null;

        try {
            $sql = "DELETE FROM $table_name WHERE $column = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode(["message" => 'Успешно!']);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    if ($data['action'] === 'update_role') {
        $role = $data['new_role'] ?? null;
        $user_id = $data['user_id'] ?? null;

        try {
            $sql = "UPDATE users_role SET role_id = :role WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            echo json_encode(['message' => 'Успешно']);
        } catch (\PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} else {
    echo 'Нет прав доступа';
}