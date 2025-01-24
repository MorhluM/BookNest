<?php

include_once '../config/connection.php';

if (!isset($_SESSION['userId'])) {
    header("Location: /booknest/in_web/login.php");
    exit();
}

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $itemId = $_POST['id'];
    $userId = $_SESSION['userId'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$itemId, $userId]);

    echo json_encode(['status' => 'success']);
    exit();
}
?>
