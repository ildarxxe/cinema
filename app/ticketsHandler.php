<?php
session_start();
use App\classes\php\Database;

require_once('./classes/php/Database.php');

$pdo = new Database();
$pdo = $pdo->getPDO();

header("Content-type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$action = $data['action'];
if ($action === "tickets") {
    $tickets = $data['tickets'];
    $price = $data['price'];
    $screening_id = $data['screening_id'];

    $tickets_str = implode(', ', $tickets);
    if (!empty($tickets) && !empty($price)) {
        try {
            $sql = "INSERT INTO tickets (screening_id, user_id, seat_number) VALUES (:screening_id, :user_id, :seat_number)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':screening_id', $_SESSION['screening_id']);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':seat_number', $tickets_str);
            $stmt->execute();

            $sql2 = "SELECT seat_number FROM tickets WHERE screening_id = :screening_id";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->bindParam(':screening_id', $screening_id);
            $stmt2->execute();
            $data = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $data = array_map(function ($row) {
                return $row['seat_number'];
            }, $data);
            $result = implode(', ', $data);

            echo json_encode(['message' => 'Билеты успешно куплены!', 'tickets' => $tickets_str, 'price' => $price, 'screen id' => $screening_id, 'data' => $result]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

if ($action === "getTickets") {
    $screening_id = $data['screening'];
    try {
        $sql = "SELECT seat_number FROM tickets WHERE screening_id = :screening_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':screening_id', $screening_id);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = array_map(function ($row) {
            return $row['seat_number'];
        }, $data);
        $result = implode(', ', $data);

        echo json_encode(['message' => 'Билеты получены!', 'screen id' => $screening_id, 'data' => $result]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}