<?php
session_start();
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

use App\classes\php\Database;

require_once('./classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

$sql_role = "SELECT role FROM users WHERE id = :id";
$sql_role = $pdo->prepare($sql_role);
$sql_role->bindParam(':id', $_SESSION['user_id']);
$sql_role->execute();
$role = $sql_role->fetch();

if ($role[0] === 'admin') {
    if ($data['action'] === 'create') {
        $inputs = $data["inputs"] ?? null;

        $phone1 = null;
        $phone2 = null;
        $phone3 = null;
        $user_id_phone = null;

        foreach ($inputs as $input => &$value) {
            if ($input == "password") {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
            if ($input == "phone1") {
                $phone1 = $value;
            }
            if ($input == "phone2") {
                $phone2 = $value;
            }
            if ($input == "phone3") {
                $phone3 = $value;
            }
            if ($input == "user_id") {
                $user_id_phone = $value;
            }
        }

        $phones = [$phone1, $phone2, $phone3];

        $keys = array_keys($inputs);
        $values = array_values($inputs);

        $keys_str = implode(", ", $keys);
        $values_str = implode(', ', array_fill(0, count($values), "?"));

        $table_name = $data["table_name"] ?? null;
        try {
            if ($table_name === "users_phone") {
                $sql = "SELECT * FROM $table_name WHERE user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $user_id_phone);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $count = 0;
                    foreach ($phones as $phone) {
                        $count++;
                        if ($phone != '') {
                            $sql = "UPDATE $table_name SET phone$count = :phone$count WHERE user_id = :user_id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(":phone$count", $phone);
                            $stmt->bindParam(":user_id", $user_id_phone);
                            $stmt->execute();
                        }
                    }
                } else {
                    $sql = "INSERT INTO $table_name (user_id) VALUES (:user_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':user_id', $user_id_phone);
                    $stmt->execute();
                    $count = 0;
                    foreach ($phones as $phone) {
                        $count++;
                        if ($phone != '') {
                            $sql = "UPDATE $table_name SET phone$count = :phone$count WHERE user_id = :user_id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(":phone$count", $phone);
                            $stmt->bindParam(":user_id", $user_id_phone);
                            $stmt->execute();
                        }
                    }
                }
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
} else {
    echo 'Нет прав доступа';
}