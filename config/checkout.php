<?php
include_once '../config/connection.php';
include_once '../config/add_to_cart.php';

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $bookId = $_POST['book_id'];
    $quantity = 1;

    // ดึงข้อมูลหนังสือเพื่อนำไปแสดงในหน้าชำระเงิน
    $stmt = $conn->prepare("SELECT * FROM book_info WHERE id = ?");
    $stmt->execute([$bookId]);
    $book = $stmt->fetch();

    if ($book) {
        $price = $book['price'];
        // คำนวณราคารวม
        $total = $price * $quantity; // Assuming $quantity is passed as a parameter or defaulted to 1
        addToCart($conn, $userId, $bookId, $quantity, $total);
    }

    // แสดงข้อมูลที่จำเป็น เช่น รายละเอียดหนังสือและรูปแบบการชำระเงิน
    // ...
    header('Location: /booknest/in_web/shopping_cart.php');
    exit();
}
