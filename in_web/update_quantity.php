<?php
include_once '../config/connection.php';

if (!isset($_SESSION['userId'])) {
    header("Location: /booknest/in_web/login.php");
    exit();
}

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['change'])) {
    $itemId = $_POST['id'];
    $change = $_POST['change'];
    $userId = $_SESSION['userId'];

    // Get current quantity
    $stmt = $conn->prepare("SELECT quantity, total FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$itemId, $userId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $currentQuantity = $item['quantity'];
        $currentTotal = $item['total'];

        // Update quantity based on change
        $newQuantity = max(0, $currentQuantity + $change);

        // Get the price of the book to calculate the new total
        $stmt = $conn->prepare("SELECT price FROM book_info WHERE id = (SELECT book_id FROM cart WHERE id = ? AND user_id = ?)");
        $stmt->execute([$itemId, $userId]);
        $price = $stmt->fetchColumn();

        // Calculate the new total
        $newTotal = $price * $newQuantity;

        if ($newQuantity <= 0) {
            $deleteStmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $deleteStmt->execute([$itemId, $userId]);
            echo json_encode(['newQuantity' => 0]);
        } else {
            // Update the cart
            $stmt = $conn->prepare("UPDATE cart SET quantity = ?, total = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$newQuantity, $newTotal, $itemId, $userId]);

            echo json_encode(['status' => 'success']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Item not found.']);
    }
    exit();
}
