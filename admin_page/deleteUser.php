<?php
include_once '../config/connection.php';

$conn = connectDB();

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $user_id = $data->id;

    // ลบข้อมูลใน cart ที่เกี่ยวข้อง
    $deleteCartSql = "DELETE FROM cart WHERE user_id = :user_id";
    $deleteCartStmt = $conn->prepare($deleteCartSql);
    $deleteCartStmt->bindParam(':user_id', $user_id);
    $deleteCartStmt->execute();

    // ลบผู้ใช้
    $deleteUserSql = "DELETE FROM users WHERE id = :id";
    $deleteUserStmt = $conn->prepare($deleteUserSql);
    $deleteUserStmt->bindParam(':id', $user_id);

    try {
        $deleteUserStmt->execute();
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
