<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../app/assets/css/style.css">
</head>
<body>
    <header class="header">
        <nav class="nav nav-tabs">
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<li class="nav-item"><a href="./app/templates/logout.php" class="nav-link">Logout</a></li><li class="nav-item"><a href="#cinema" class="nav-link">Cinema</a></li>';
            } else {
                echo '<li class="nav-item"><a href="#auth" class="nav-link">Auth</a></li>
            <li class="nav-item"><a href="#reg" class="nav-link">Register</a></li>';
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
                    $sql = "SELECT name FROM users WHERE id = :user_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['user_id' => $user_id]);
                    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $username = $user[0]['name'];

                    echo '<p class="profile header__other">Профиль: ' . $username . '</p>';
                }
                ?>
        </nav>
    </header>
    <div id="root"></div>
</body>
<script src="./app/script.js" type="module"></script>
</html>