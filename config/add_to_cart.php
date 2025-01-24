<?php

include_once '../config/connection.php';

$conn = connectDB();

function addToCart($conn, $userId, $bookId, $quantity, $total)
{
    // ตรวจสอบว่าสินค้าอยู่ในตะกร้าหรือไม่
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$userId, $bookId]);
    $existingItem = $stmt->fetch();

    if ($existingItem) {
        // ถ้าสินค้าอยู่ในตะกร้าแล้ว ให้เพิ่มจำนวน
        $newQuantity = $existingItem['quantity'] + $quantity;
        $newTotal = $existingItem['total'] * $quantity; // เพิ่มราคารวม
        $stmt = $conn->prepare("UPDATE cart SET quantity = ?, total = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $newTotal, $existingItem['id']]);
    } else {
        // ถ้าสินค้าไม่อยู่ในตะกร้า ให้เพิ่มรายการใหม่
        $stmt = $conn->prepare("INSERT INTO cart (user_id, book_id, quantity, total) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $bookId, $quantity, $total]);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $bookId = $_POST['book_id'];
    $quantity = 1;

    // ดึงราคา (price) ของหนังสือจากฐานข้อมูล
    $stmt = $conn->prepare("SELECT price FROM book_info WHERE id = ?");
    $stmt->execute([$bookId]);
    $book = $stmt->fetch();

    if ($book) {
        $price = $book['price'];
        // คำนวณราคารวม
        $total = $price * $quantity; // Assuming $quantity is passed as a parameter or defaulted to 1
        addToCart($conn, $userId, $bookId, $quantity, $total);
    }


    // Redirect กลับไปยังหน้าก่อนหน้านี้
    header('Location: /booknest/in_web/book.php');
    exit();
}
