<?php

namespace App\classes\php;

use PDOException;

session_start();

class User implements Model
{
    private object $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($table_name, $data): bool|string
    {
        $name_value = $data['name'];
        $email_value = $data['email'];
        $password_value = null;
        $phone1 = $data['phone1'];
        $phone2 = $data['phone2'] ?? '';
        $phone3 = $data['phone3'] ?? '';

        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $password_value = password_hash($value, PASSWORD_DEFAULT);
            }
            if ($key === 'phone2') {
                $phone2 = $value;
            }
            if ($key === 'phone3') {
                $phone3 = $value;
            }
        }
        try {
            $this->pdo->beginTransaction();
            $sql_users = "INSERT INTO $table_name (name, email, password) VALUES (:name, :email, :password)";
            $stmt_users = $this->pdo->prepare($sql_users);
            $stmt_users->bindParam(':name', $name_value);
            $stmt_users->bindParam(':email', $email_value);
            $stmt_users->bindParam(':password', $password_value);
            $stmt_users->execute();

            $user_id = $this->pdo->lastInsertId();


            $sql_phones = "INSERT INTO users_phone (user_id, phone1, phone2, phone3) VALUES (?, ?, ?, ?)";
            $stmt_phones = $this->pdo->prepare($sql_phones);
            $stmt_phones->execute([$user_id, $phone1, $phone2, $phone3]);
            $this->pdo->commit();
            return true;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
            return false;
        }
    }

    public
    function read($table_name, $data): bool|string
    {
        $email_value = null;
        $password_value = null;
        foreach ($data as $key => $value) {
            if ($key === "email") {
                $email_value = $value;
            }
            if ($key === "password") {
                $password_value = $value;
            }
        }

        if (!empty($email_value)) {
            $sql = "SELECT * FROM $table_name WHERE email = :email";

            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(":email", $email_value);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                    if (password_verify($password_value, $user["password"])) {
                        $_SESSION["user_id"] = $user["id"];
                        return true;
                    } else {
                        return "Неправильный пароль";
                    }
                } else {
                    return "Пользователь не найден";
                }
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        } else {
            return 'Email или пароль неправильные';
        }
    }

    public
    function update($table_name, $data): bool|string
    {
        if ($data['action'] === "put_password") {
            $current_password = $data["current_password"];
            $new_password = $data["change_password"];
            $new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $id = $_SESSION["user_id"];

            $sql = "SELECT password FROM $table_name WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $password = $result[0]["password"];
            if (password_verify($current_password, $password)) {
                $sql2 = "UPDATE $table_name SET password = :password WHERE id = :id";
                $stmt2 = $this->pdo->prepare($sql2);
                $stmt2->bindParam(":password", $new_password);
                $stmt2->bindParam(":id", $id);
                $stmt2->execute();
                return true;
            } else {
                return 'Неверный пароль';
            }
        } else {
            $name = $data['name'] ?? null;
            $email = $data['email'] ?? null;
            $id = $_SESSION['user_id'] ?? null;

            $sql = "UPDATE $table_name SET name = :name, email = :email WHERE id = :id";
            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(":name", $name);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":id", $id);
                $stmt->execute();
                return true;
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }

    public
    function delete($table_name, $data): bool|string
    {
        $id = $data['id'] ?? null;
        $delete_password = $data['delete_password'] ?? null;

        try {
            $sql = "SELECT password FROM $table_name WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $password = $result["password"];

            if (password_verify($delete_password, $password)) {
                $sql2 = "DELETE FROM tickets WHERE user_id = :id";
                $stmt2 = $this->pdo->prepare($sql2);
                $stmt2->bindParam(":id", $id);
                $stmt2->execute();

                $sql3 = "DELETE FROM $table_name WHERE id = :id";
                $stmt3 = $this->pdo->prepare($sql3);
                $stmt3->bindParam(":id", $id);
                $stmt3->execute();

                session_unset();
                session_destroy();

                return true;
            } else {
                return 'Пароль неверный';
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}