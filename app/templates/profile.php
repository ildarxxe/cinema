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

    $sql_phones = "SELECT * FROM users_phone WHERE user_id = :id";
    $stmt_phones = $pdo->prepare($sql_phones);
    $stmt_phones->bindParam(":id", $user_id);
    $stmt_phones->execute();
    $result_phones = $stmt_phones->fetchAll(PDO::FETCH_ASSOC);
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
<?php

?>
<div class="profile">
    <div class="profile__inner">
        <h1 class="profile__title tac">Ваш профиль</h1>
        <form class="profile__form">
            <div class="form__inner">
                <h3 class="profile__name">Имя: <?= $name ?></h3>
                <h3 class="profile__email">Email: <?= $email ?></h3>
                <?php
                foreach ($result_phones as $item => $value) { ?>
                    <h4 class="profile__phone">Номер телефона <?= $item + 1 ?>: <?= $value['phones'] ?></h4>
                <?php } ?>
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
                    <span class="form__error"></span>
                </label>
                <label class="form__label">
                    Email:
                    <input class="form-control" type="email" value="<?= $email ?>" name="change_email"
                           id="change_email">
                    <span class="form__error"></span>
                </label>
                <div class="phones">
                    <?php
                    foreach ($result_phones as $item => $value) { ?>
                        <label class="form__label form__label--add">
                            <div class="number_count">
                                Номер телефона <span class="count"></span>:
                            </div>
                            <div class="additional__phone">
                                <input class="form-control input__phone" type="text" value="<?= $value['phones'] ?>" name="change_phone<?= $item + 1 ?>">
                                <button class="close_phone" type="button">-</button>
                            </div>
                            <span class="form__error"></span>
                        </label>
                    <?php } ?>
                </div>
                <button type="button" class="add_phone">+</button>
                <input type="button" value="Изменить" class="form__submit edit_profile_submit profile__submit">
            </div>
        </div>
        <div class="hidden form form_change_password">
            <div class="form__inner">
                <button type="button" class="form__close">X</button>
                <label class="form__label">
                    Ваш пароль:
                    <input type="password" class="form-control" name="current_password" id="current_password">
                    <span class="form__error"></span>
                </label>
                <label class="form__label">
                    Новый пароль:
                    <input class="form-control" type="password" name="change_password"
                           id="change_password">
                    <span class="form__error"></span>
                </label>
                <input type="button" disabled value="Изменить"
                       class="profile__submit form__submit change_password_submit">
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
