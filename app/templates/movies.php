<?php

use App\classes\php\Database;

require_once('../classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();


$cinema_id = $_GET['id'];

$try = false;
try {
    $sql = "SELECT * FROM movies WHERE cinema_id = :cinema_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cinema_id', $cinema_id);
    $stmt->execute();
    $cinema = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $try = true;

    $sql2 = "SELECT cinema_name FROM cinemas WHERE cinema_id = :cinema_id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':cinema_id', $cinema_id);
    $stmt2->execute();
    $result = $stmt2->fetch();
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

?>

<div class="movies">
    <div class="movies__inner">
        <h1 class="cinema__name"><?= $result[0] ?></h1>
        <div class="movies__cards">
            <?php foreach ($cinema as $item) {
                if (!empty($item)) {?>
                    <div class="movies__card" data-key="<?= $item['movie_id'] ?>">
                        <h2 class="movies__name"><?= $item['title'] ?></h2>
                        <p class="movies__desc"><?= $item['description'] ?></p>
                        <p class="movies__duration">Длительность: <?= $item['duration'] ?> мин.</p>
                    </div>
                <?php } else {echo 'Фильмов нет';} } ?>
        </div>
    </div>
</div>
