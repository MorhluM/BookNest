<?php

include_once '../config/connection.php';

if (!isset($_SESSION['userId'])) {
    header("Location: /booknest/in_web/login.php");
    exit();
}

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $deleteStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $deleteStmt->execute([$_SESSION['userId']]);

    header("Location: /booknest/in_web/book.php");
    exit();
}
