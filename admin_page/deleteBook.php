<?php
include_once '../config/connection.php';

$conn = connectDB();

// รับข้อมูลจาก AJAX
$data = json_decode(file_get_contents("php://input"), true);
$bookId = $data['id'];

// เตรียมคำสั่ง SQL เพื่อลบผู้ใช้
$sql = "DELETE FROM book_info WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $bookId, PDO::PARAM_INT);

// ลบผู้ใช้
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not delete user.']);
}
?>
