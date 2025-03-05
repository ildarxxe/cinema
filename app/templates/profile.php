<?php
session_start();
use App\classes\php\Database;

require_once('../classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

$user_id = $_SESSION['user_id'];

try {
    $sql = "SELECT name, email FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $user_id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$name = null;
$email = null;

foreach ($result as $row) {
    $name = $row['name'];
    $email = $row['email'];
}

?>

<div class="profile">
    <div class="profile__inner">
        <h1 class="profile__title tac">Ваш профиль</h1>
        <form class="profile__form">
            <div class="form__inner">
                <h3 class="profile__name">Имя: <?= $name ?></h3>
                <h3 class="profile__email">Email: <?= $email ?></h3>
                <a class="change_password action_profile">Изменить пароль</a>
                <a class="edit_profile action_profile">Редактировать профиль</a>
                <button type="button" class="delete_profile">Удалить профиль</button>
            </div>
        </form>
        <div class="hidden form form_edit_profile">
            <div class="form__inner">
                <button type="button" class="form__close">X</button>
                <label class="form__label">
                    Имя:
                    <input type="text" class="form-control" value="<?= $name ?>" name="change_name" id="change_name">
                </label>
                <label class="form__label">
                    Email:
                    <input class="form-control" type="email" value="<?= $email ?>" name="change_email"
                        id="change_email">
                </label>
                <input type="button" value="Изменить" class="form__submit edit_profile_submit">
            </div>
        </div>
        <div class="hidden form form_change_password">
            <div class="form__inner">
                <button type="button" class="form__close">X</button>
                <label class="form__label">
                    Ваш пароль:
                    <input type="password" class="form-control" name="current_password" id="current_password">
                </label>
                <label class="form__label">
                    Новый пароль:
                    <input class="form-control" type="password" name="change_password"
                           id="change_password">
                </label>
                <input type="button" value="Изменить" class="form__submit change_password_submit">
            </div>
        </div>
        <div class="hidden form form_delete_profile">
            <div class="form__inner">
                <button type="button" class="form__close">X</button>
                <label class="form__label">
                    Введите пароль:
                    <input type="password" name="delete_password" id="delete_password" class="form-control">
                </label>
                <input type="button" value="Подтвердить" class="form__submit delete_profile_submit">
            </div>
        </div>
    </div>
</div>