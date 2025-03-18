<?php

session_start();

use App\classes\php\Database;

require_once('../classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

$table_name = $_GET['table'];
$user_id = $_SESSION['user_id'];

$sql_role = "SELECT role_id FROM users_role WHERE user_id = :user_id";
$stmt_role = $pdo->prepare($sql_role);
$stmt_role->bindParam(":user_id", $user_id);
$stmt_role->execute();
$res_role = $stmt_role->fetch(PDO::FETCH_ASSOC);
$role = $res_role['role_id'];

if ($role == 2) {
    try {
        $sql = "SELECT * FROM `$table_name`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<div class='table_box'>";
        echo "<h2>Данные таблицы: " . htmlspecialchars($table_name) . "</h2>";
        echo "<table class='admin__table'>";
        echo "<thead><tr>";

        if (!empty($result)) {
            foreach (array_keys($result[0]) as $column) {
                echo "<th>" . htmlspecialchars($column) . "</th>";
            }

            echo "</tr></thead><tbody>";
            $sql_roles = "SELECT * FROM roles";
            $stmt_roles = $pdo->prepare($sql_roles);
            $stmt_roles->execute();
            $res_roles = $stmt_roles->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                $first_column = array_key_first($row);
                $id = $row[$first_column];
                echo "<tr>";
                if ($table_name == "users_role") {
                    $count = 0;
                    foreach ($row as $value) {
                        $count++;
                        if ($count == 1) {
                            echo "<td class='selected_role'><select>";
                            foreach ($res_roles as $role) {
                                if ($role['role_id'] == $row['role_id']) {
                                    echo "<option selected value='" . htmlspecialchars($role['role_id']) . "'>" . htmlspecialchars($role['role_id']) . "</option>";
                                } else {
                                    echo "<option value='" . htmlspecialchars($role['role_id']) . "'>" . htmlspecialchars($role['role_id']) . "</option>";
                                }
                            }
                            echo "</select></td>";
                        } else {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                    }
                } else {
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                }
                echo "<td class='delete__row' data-id='" . htmlspecialchars($id) . "' data-value='" . htmlspecialchars($first_column) . "' data-table='" . htmlspecialchars($table_name) . "'>Удалить</td></tr>";
            }
            echo "</tbody></table></div>";
            echo "<form class='admin__form'>";
            if ($table_name != "users_role" && $table_name != "roles") {
                $sliced_array = array_slice(array_keys($result[0]), 1);
                foreach ($sliced_array as $column) {
                    echo "<input type='text' name='" . htmlspecialchars($column) . "' id='" . htmlspecialchars($column) . "' placeholder='" . htmlspecialchars($column) . "' />";
                }
            } else {
                foreach ($result[0] as $item => $value) {
                    echo "<input type='text' name='" . htmlspecialchars($item) . "' id='" . htmlspecialchars($item) . "' placeholder='" . htmlspecialchars($item) . "' />";
                }
            }
            echo "<button type='button' class='insert_into_table' data-table='" . htmlspecialchars($table_name) . "'>Добавить</button></form>";
        } else {
            echo '<p>Таблица пустая</p>';
        }

    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
} else {
    echo '<p>Нет прав доступа</p>';
}
?>