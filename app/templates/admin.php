<?php

use App\classes\php\Database;

require_once('../classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

try {
    $sql_show_tables = "SHOW TABLES";
    $stmt = $pdo->prepare($sql_show_tables);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<div class="admin">
    <div class="admin__inner">
        <h1 class="admin__title">Таблицы</h1>
        <div class="admin__tables">
            <?php foreach ($result as $row) { ?>
                <a class="admin__link" data-tablename="<?= $row ?>"  ><?= $row ?></a>
            <?php } ?>
        </div>
        <div class="table__content"></div>
    </div>
</div>
