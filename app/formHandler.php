<?php

use App\classes\php\Database;
use App\classes\php\User;

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
        $phones = $data["phones_arr"] ?? [];

        if (!empty($name) || !empty($email) || !empty($password) || !empty($phone1)) {
            $result = $user->create($user_table_name, ['name' => $name, 'email' => $email, 'password' => $password, 'phones' => $phones]);
            if ($result === true) {
                echo json_encode(['message' => 'Пользователь создан!', 'redirect' => '../index.php#auth']);
            } else {
                echo json_encode(['error' => $result]);
            }
        } else {
            echo json_encode(['error' => $data]);
        }
        break;
    case "read":
        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;

        if (!empty($email) || !empty($password)) {
            $result = $user->read($user_table_name, ['email' => $email, 'password' => $password]);
            if ($result === true) {
                echo json_encode(['message' => 'Пользователь найден!', 'redirect' => '../index.php#cinemas']);
            } else {
                echo json_encode(['error' => $result]);
            }
        }
        break;
    case 'put_profile':
        $name = $data['change_name'] ?? null;
        $email = $data['change_email'] ?? null;
        $phones = $data['phones'] ?? [];

        $result = $user->update($user_table_name, ['name' => $name, 'email' => $email, 'phones' => $phones, 'action' => 'put_profile']);
        if ($result === true) {
            echo json_encode(['message' => 'Изменения успешно сохранены!', 'redirect' => '../index.php#profile']);
        } else {
            echo json_encode(['error' => $result]);
        }
        break;

    case 'put_password':
        $current_password = $data["current_password"] ?? null;
        $change_password = $data['change_password'] ?? null;

        $result = $user->update($user_table_name, ['current_password' => $current_password, 'change_password' => $change_password, 'action' => 'put_password']);
        if ($result === true) {
            echo json_encode(['message' => 'Ваш пароль успешно обновлен!']);
        } else {
            echo json_encode(['error' => $result]);
        }
        break;
    case 'delete':
        $id = $_SESSION["user_id"] ?? null;
        $delete_password = $data["delete_password"] ?? null;

        $result = $user->delete($user_table_name, ['id' => $id, 'delete_password' => $delete_password]);

        if ($result === true) {
            echo json_encode(['message' => 'Аккаунт удален!', 'redirect' => '../index.php#auth']);
        } else {
            echo json_encode(['error' => $result]);
        }
        break;
    default:
        echo json_encode(['error' => 'Некорректное действие']);
}