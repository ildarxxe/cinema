<?php
session_start();

use App\classes\php\Database;

require_once('../classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();


$screening_id = $_GET['id'];
$_SESSION['screening_id'] = $screening_id;
$hall_number = $_GET['hall'];
$movie_id = $_GET['movie'];
$price = $_GET['price'];

try {
    $sql = "SELECT * FROM screenings WHERE screening_id = :screening_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':screening_id', $screening_id);
    $stmt->execute();
    $screenings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $hall_id = $screenings[0]['hall_id'];

    $sql2 = "SELECT * FROM halls WHERE hall_id = :hall_id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':hall_id', $hall_id);
    $stmt2->execute();
    $result = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $sql3 = "SELECT title FROM movies WHERE movie_id = :movie_id";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->bindParam(':movie_id', $movie_id);
    $stmt3->execute();
    $movie_title = $stmt3->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

$capacity = $result[0]['capacity'];
$hall_number = $result[0]['hall_number'];
$movie_title = $movie_title[0]['title'];

?>

<div class="hall">
    <div class="hall__inner">
        <h1 class="hall__title tac">Зал №<?= $hall_number ?> <span class="price">(цена: <?= $price ?> тг.)</span></h1>
        <h2 class="hall_movie tac">Фильм: <?= $movie_title ?></h2>
        <div class="screen tac">Экран</div>
        <div class="buy">
            <h3 class="summa">Общая стоимость: <span class="price__summ"></span> тг.</h3>
            <button class="buy_tickets">Купить билеты</button>
        </div>
        <div class="hall__capacity">
            <?php for ($i = 1; $i <= $capacity; $i++) { ?>
                <div class="cell" data-cell="<?= $i ?>"><?= $i ?></div>
            <?php } ?>
        </div>
    </div>
</div>

