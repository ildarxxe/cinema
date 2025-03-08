<?php

use App\classes\php\Database;

require_once('../classes/php/Database.php');
$pdo = new Database();
$pdo = $pdo->getPDO();
$sql = "SELECT * FROM cinemas";
$try = false;
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $try = true;
} catch (PDOException $e) {
    echo $e->getMessage();
    $try = false;
}

if ($try == true) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $result = null;
}
?>
<main class="content">
    <div class="cinema">
        <div class="cinema__inner mt-3">
            <div class="cinema__title">
                <h1 class="text-center">Наши кинотеатры</h1>
            </div>
            <div class="cinema__content container mt-5 text-white">
                <?php
                foreach ($result as $value) {
                    ?>
                        <div class="cinema__card" data-key="<?= $value['cinema_id'] ?>">
                            <h3 class="cinema__name"><a class="cinema__link" href="../../index.php#movies?id=<?= $value['cinema_id']?>"><?= $value['cinema_name'] ?></a></h3>
                            <p class="cinema__address">Адрес: <?= $value['cinema_address'] ?></p>
                        </div>
                <?php } ?>

            </div>
        </div>
    </div>
</main>