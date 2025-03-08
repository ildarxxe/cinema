<?php
use App\classes\php\Database;

require_once('../classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();


$movie_id = $_GET['id'];

try {
    $sql = "SELECT * FROM screenings WHERE movie_id = :movie_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':movie_id', $movie_id);
    $stmt->execute();
    $screenings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql2 = "SELECT title FROM movies WHERE movie_id = :movie_id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':movie_id', $movie_id);
    $stmt2->execute();
    $title = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $hall_id = $screenings[0]['hall_id'];
    $sql3 = "SELECT hall_number FROM halls WHERE hall_id = :hall_id";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->bindParam(':hall_id', $hall_id);
    $stmt3->execute();
    $hall_number = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    $hall_number = $hall_number[0]['hall_number'];
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

?>

<div class="screenings">
    <div class="screenings__inner">
        <h1 class="screenings__title tac">Доступные сеансы</h1>
        <div class="screenings__cards">
            <?php foreach ($screenings as $item) { ?>
                <div class="screenings__card" data-key="<?= $item['screening_id'] ?>" data-hall="<?= $hall_number ?>" data-movie="<?= $movie_id ?>" data-price="<?= $item['price'] ?>" data-screening="<?= $item['screening_id'] ?>">
                    <h2 class="screenings__hall--number tac">Номер зала: <?= $hall_number ?></h2>
                    <h3 class="screenings__movie--name">Фильм: <?= $title[0]['title'] ?></h3>
                    <p class="screenings__date">Дата начала: <?= $item['start_time'] ?></p>
                    <p class="screenings__price">Цена: <?= $item['price'] ?> тг.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
