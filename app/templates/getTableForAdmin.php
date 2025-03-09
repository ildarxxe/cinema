<?php

session_start();

use App\classes\php\Database;

require_once('../classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

$table_name = $_GET['table'];

$sql_role = "SELECT role FROM users WHERE id = :id";
$sql_role = $pdo->prepare($sql_role);
$sql_role->bindValue(':id', $_SESSION['user_id']);
$sql_role->execute();
$role = $sql_role->fetch();

if ($role[0] === 'admin') {
    try {
        $sql = "SELECT * FROM `$table_name`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $html = "<div class='table_box'>";
        $html .= "<h2>Данные таблицы: " . $table_name . "</h2>";
        $html .= "<table class='admin__table'>";
        $html .= "<thead><tr>";

        if (!empty($result)) {
            foreach (array_keys($result[0]) as $column) {
                $html .= "<th>" . $column . "</th>";
            }

            $html .= "</tr></thead><tbody>";
            foreach ($result as $row) {
                $first_column = array_key_first($row);
                $id = $row[$first_column];
                $html .= "<tr>";
                foreach ($row as $value) {
                    $html .= "<td>" . $value . "</td>";
                }
                $html .= "<td class='delete__row' data-id='$id' data-value='$first_column' data-table=$table_name>Удалить</td></tr>";
            }
            $html .= "</tbody></table></div>";
            $html .= "<form class='admin__form'>";
            $sliced_array = array_slice(array_keys($result[0]), 1);
            foreach ($sliced_array as $column) {
                $html .= "<input type='text' name='$column' id='$column' placeholder='$column' />";
            }
            $html .= "<button type='button' class='insert_into_table' data-table=$table_name>Добавить</button></form>";
        } else {
            $html .= 'Таблица пустая';
        }
        echo $html;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo 'Нет прав доступа';
}