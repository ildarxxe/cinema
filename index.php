<?php

session_start();

use App\classes\php\Database;

require_once('./vendor/autoload.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

if (!isset($_SESSION['redirected'])) {

    if (isset($_SESSION["user_id"])) {
        header('Location: index.php#');
        $_SESSION['redirected'] = true;
        exit;
    } else {
        header('Location: index.php#auth');
        $_SESSION['redirected'] = true;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Cinema</title>
    <link rel="stylesheet" href="../app/assets/css/style.css">
</head>
<body>
<header class="header">
    <nav class="nav nav-tabs">
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<a href="./app/templates/logout.php" class="nav-link">Выйти</a>';
        }
        ?>
        <a class="nav-link home">Главная</a>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<a href="#cinemas" class="nav-link">Кинотеатры</a>';
        } else {
            echo '<a href="#auth" class="nav-link">Авторизация</a>
            <a href="#reg" class="nav-link">Регистрация</a>';
        }
        ?>
        <?php
        $session_data = $_SESSION;
        echo '<script>let session_data = ' . json_encode($session_data) . '</script>';
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT * FROM users WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $username = $user[0]['name'] ?? null;
            
            $sql_role = "SELECT role_id FROM users_role WHERE user_id = :user_id";
            $stmt_role = $pdo->prepare($sql_role);
            $stmt_role->bindParam(":user_id", $user_id);
            $stmt_role->execute();
            $res_role = $stmt_role->fetch(PDO::FETCH_ASSOC);
            $role = $res_role['role_id'];
            if ($username !== null) {
                echo '<a class="profile nav-link" href="#profile">Профиль: ' . $username . '</a>';
                if ($role == 2 ) {
                    echo '<a class="admin_panel nav-link" href="#admin_panel">Панель админа</a><script>let admin = true;</script>';
                } else {
                    echo '<script>let admin = false;</script>';
                }
            }
        }
        ?>
    </nav>
</header>
<div id="root">
</div>
</body>
<script src="./app/script.js" type="module"></script>
</html>