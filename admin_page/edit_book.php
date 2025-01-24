<?php
include_once '../config/connection.php';

// เชื่อมต่อฐานข้อมูล
$conn = connectDB();

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    // ดึงข้อมูลหนังสือตาม ID
    $sql = "SELECT * FROM book_info WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // ถ้าไม่มี ID ส่งกลับไปที่หน้า admin_dashboard.php
    header('Location: /booknest/admin_page/admin_dashboard.php');
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $rate = $_POST['rate'];
    $categories = $_POST['categories'];
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;
    $is_new_release = isset($_POST['is_new_release']) ? 1 : 0;

    // เก็บรูปภาพเดิม
    $currentImage = $book['image'];

    // ตรวจสอบการอัพโหลดรูปภาพ
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // อัพโหลดรูปภาพใหม่
        $image = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        // ใช้รูปภาพเดิม
        $image = $currentImage;
    }

    // อัปเดตข้อมูลหนังสือ
    $updateSql = "UPDATE book_info SET name = :name, author = :author, price = :price, 
                  rate = :rate, categories = :categories, is_best_seller = :is_best_seller, 
                  is_new_release = :is_new_release, image = :image WHERE id = :id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':name', $name);
    $updateStmt->bindParam(':author', $author);
    $updateStmt->bindParam(':price', $price);
    $updateStmt->bindParam(':rate', $rate);
    $updateStmt->bindParam(':categories', $categories);
    $updateStmt->bindParam(':is_best_seller', $is_best_seller, PDO::PARAM_INT);
    $updateStmt->bindParam(':is_new_release', $is_new_release, PDO::PARAM_INT);
    $updateStmt->bindParam(':image', $image); // bind the image
    $updateStmt->bindParam(':id', $bookId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo '<script>alert("Update book data success!"); window.location.href = "/booknest/admin_page/admin_dashboard.php";</script>';
    } else {
        echo "Error updating book.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Book</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-20 p-6 bg-white rounded-lg shadow-lg max-w-2xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Edit Book</h2>
        <form method="POST" enctype="multipart/form-data" action="">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                <input type="text" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" name="name" value="<?php echo htmlspecialchars($book['name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="author" class="block text-gray-700 font-medium mb-2">Author</label>
                <input type="text" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-gray-700 font-medium mb-2">Price</label>
                <input type="number" step="0.01" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="price" name="price" value="<?php echo htmlspecialchars($book['price']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="rate" class="block text-gray-700 font-medium mb-2">Rate</label>
                <input type="number" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="rate" name="rate" value="<?php echo htmlspecialchars($book['rate']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="categories" class="block text-gray-700 font-medium mb-2">Categories</label>
                <input type="text" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="categories" name="categories" value="<?php echo htmlspecialchars($book['categories']); ?>" required>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" class="mr-2 h-5 w-5" id="is_best_seller" name="is_best_seller" <?php echo $book['is_best_seller'] ? 'checked' : ''; ?>>
                <label class="text-gray-700" for="is_best_seller">Best Seller</label>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" class="mr-2 h-5 w-5" id="is_new_release" name="is_new_release" <?php echo $book['is_new_release'] ? 'checked' : ''; ?>>
                <label class="text-gray-700" for="is_new_release">New Release</label>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-medium mb-2">Image</label>
                <input type="file" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="image" name="image">
            </div>
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition">Save Changes</button>
                <a href="admin_dashboard.php" class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>

