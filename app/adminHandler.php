<?php
session_start();
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$inputs = $data["inputs"] ?? null;

foreach ($inputs as $input => &$value) {
    if ($input == "password") {
        $value = password_hash($value, PASSWORD_DEFAULT);
    }
}

$keys = array_keys($inputs);
$values = array_values($inputs);

$keys_str = implode(", ", $keys);
$values_str = implode(', ', array_fill(0, count($values), "?"));

$table_name = $data["table_name"] ?? null;

use App\classes\php\Database;

require_once('./classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

$sql_role = "SELECT role FROM users WHERE id = :id";
$sql_role = $pdo->prepare($sql_role);
$sql_role->bindValue(':id', $_SESSION['user_id']);
$sql_role->execute();
$role = $sql_role->fetch();

if ($role[0] === 'admin') {
try {
    $sql = "INSERT INTO $table_name ($keys_str) VALUES ($values_str)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    echo json_encode(["message" => 'Успешно!']);
} catch (\PDOException $e) {
    echo $e->getMessage();
    echo json_encode(["error" => $e->getMessage()]);
}} else {
    echo 'Нет прав доступа';
}