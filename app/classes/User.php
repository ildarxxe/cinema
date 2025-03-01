<?php 
namespace App\classes;
session_start();
class User implements Model {
    private object $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($table_name, $data): bool|string
    {
        $keys = array_keys($data);
        $values = array_values($data);
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $index = array_search($key, $keys);
                $values[$index] = password_hash($value, PASSWORD_DEFAULT);
                break;
            }
        }

        $keys_string = implode(", ", $keys);
        $placeholders = array_fill(0, count($values), "?");
        $values_string = implode(", ", $placeholders);

        $sql = "INSERT INTO $table_name ($keys_string) VALUES ($values_string)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            return true;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }

    public function read($table_name, $data): bool|string {
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
                        return "Wrong password";
                    }
                } else {
                    return "User not found";
                }
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        } else {
            return 'email or password missing';
        }
    }

    public function update() {

    }
    public function delete() {

    }
}