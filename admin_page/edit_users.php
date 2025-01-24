<?php
include_once '../config/connection.php';

// เชื่อมต่อฐานข้อมูล
$conn = connectDB();

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // ดึงข้อมูลหนังสือตาม ID
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // ถ้าไม่มี ID ส่งกลับไปที่หน้า admin_dashboard.php
    header('Location: /booknest/admin_page/manage_user.php');
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // เก็บรูปภาพเดิม
    $currentImage = $users['image'];

    // ตรวจสอบการอัพโหลดรูปภาพ
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // อัพโหลดรูปภาพใหม่
        $image = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        // ใช้รูปภาพเดิม
        $image = $currentImage;
    }

    // อัปเดตข้อมูลหนังสือ
    $updateSql = "UPDATE users SET user_name = :user_name, email = :email, first_name = :first_name, 
                  last_name = :last_name, address = :address, phone = :phone, 
                  image = :image WHERE id = :id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':user_name', $user_name);
    $updateStmt->bindParam(':email', $email);
    $updateStmt->bindParam(':first_name', $first_name);
    $updateStmt->bindParam(':last_name', $last_name);
    $updateStmt->bindParam(':address', $address);
    $updateStmt->bindParam(':phone', $phone);
    $updateStmt->bindParam(':image', $image); // bind the image
    $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo '<script>alert("Update user data success!"); window.location.href = "/booknest/admin_page/manage_user.php";</script>';
    } else {
        echo "Error updating user.";
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
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-2xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Edit Users</h2>
        <form method="POST" enctype="multipart/form-data" action="">
            <div class="mb-4">
                <label for="user_name" class="block text-gray-700 font-medium mb-2">Username</label>
                <input type="text" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="user_name" name="user_name" value="<?php echo htmlspecialchars($users['user_name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="text" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" name="email" value="<?php echo htmlspecialchars($users['email']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700 font-medium mb-2">First Name</label>
                <input type="text" step="0.01" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="first_name" name="first_name" value="<?php echo htmlspecialchars($users['first_name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-gray-700 font-medium mb-2">Last Name</label>
                <input type="text" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="last_name" name="last_name" value="<?php echo htmlspecialchars($users['last_name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-medium mb-2">Address</label>
                <input type="text" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="address" name="address" value="<?php echo htmlspecialchars($users['address']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                <input type="text" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="phone" name="phone" value="<?php echo htmlspecialchars($users['phone']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-medium mb-2">Image</label>
                <input type="file" class="border border-gray-300 rounded-lg w-3/4 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="image" name="image">
                <?php if (!empty($users['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($users['image']); ?>" width="100" height="100" alt="Current Image">
                <?php endif; ?>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition">Save Changes</button>
                <a href="admin_dashboard.php" class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>