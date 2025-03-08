<?php

session_start();

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
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=grand_cinema', 'root', 'admin');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $sql = "SELECT * FROM users WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $username = $user[0]['name'] ?? null;
            $role = $user[0]['role'] ?? null;

            if ($username !== null) {
                echo '<a class="profile nav-link" href="#profile">Профиль: ' . $username . '</a>';
                if ($role === 'admin') {
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