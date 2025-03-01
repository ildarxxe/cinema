<?php

use App\classes\Database;
use App\classes\User;

require_once('../vendor/autoload.php');

$pdo = new Database();
$pdo = $pdo->getPDO();
$user = new User($pdo);
$user_table_name = 'users';

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Ошибка декодирования JSON: ' . json_last_error_msg()]);
    exit;
}

$action = $data["action"];

switch ($action) {
    case "create":
        $name = $data["name"] ?? null;
        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;

        if (!empty($name) || !empty($email) || !empty($password)) {
            $result = $user->create($user_table_name, ['name' => $name, 'email' => $email, 'password' => $password]);
            if ($result === true) {
                echo json_encode(['message' => 'Пользователь создан']);
            } else {
                echo json_encode(['error' => $result]);
            }
        }
        break;
    case "read":
        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;

        if (!empty($email) || !empty($password)) {
            $result = $user->read($user_table_name, ['email' => $email, 'password' => $password]);
            if ($result === true) {
                echo json_encode(['message' => 'Пользователь найден!', 'redirect' => '../index.php']);
            } else {
                echo json_encode(['error' => $result]);
            }
        }
        break;
    default:
        echo json_encode(['error' => 'Некорректное действие']);
}