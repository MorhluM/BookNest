<?php
include_once '../config/connection.php';

$conn = connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'] ?? null;
    $author = $_POST['author'] ?? null;
    $price = $_POST['price'] ?? null;
    $rate = $_POST['rate'] ?? null;
    $categories = $_POST['categories'] ?? null;
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;
    $is_new_release = isset($_POST['is_new_release']) ? 1 : 0;


    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

        echo json_encode(['success' => false, 'message' => 'Error uploading image']);
        exit();
    }


    $sql = "INSERT INTO book_info (name, author, price, rate, categories, image, is_best_seller, is_new_release) 
            VALUES (:name, :author, :price, :rate, :categories, :image, :is_best_seller, :is_new_release)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':rate', $rate);
    $stmt->bindParam(':categories', $categories);
    $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
    $stmt->bindParam(':is_best_seller', $is_best_seller);
    $stmt->bindParam(':is_new_release', $is_new_release);

    try {
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Book inserted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit();
}
