<?php

include '../config/connection.php';
$conn = connectDB();

if (!isset($_SESSION['userId'])) {
    header("Location: /booknest/in_web/login.php");
    exit();
}

$userId = $_SESSION['userId']; // ตรวจสอบว่ามีการเก็บค่า session นี้หรือไม่

// Prepare the SQL statement using PDO
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $_SESSION['userId'], PDO::PARAM_INT);
$stmt->execute();

// Fetch the user data
$row = $stmt->fetch();

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

</head>

<body class="bg-[#F2EBE3]">

    <div>
        <?php include('../main_design/nav.php') ?>

        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ค่าที่ได้จากฟอร์ม
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $user_name = $_POST['user_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $userId = $_SESSION['userId'];

            // การจัดการการอัปโหลดรูปโปรไฟล์
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $imageData = file_get_contents($_FILES['profile_picture']['tmp_name']);
            } else {
                // หากไม่มีการอัปโหลดรูปภาพ ใช้ค่าที่มีอยู่เดิม
                $stmt = $conn->prepare("SELECT image FROM users WHERE id = :id");
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch();
                $imageData = $row['image']; // ใช้ภาพเดิม
            }

            // อัปเดตข้อมูลในฐานข้อมูล
            $updateSql = "UPDATE users SET first_name = :first_name, last_name = :last_name, user_name = :user_name, email = :email, phone = :phone, address = :address, image = :image WHERE id = :id";
            $stmt = $conn->prepare($updateSql);

            // Bind parameters
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB); // ใช้ PDO::PARAM_LOB สำหรับ BLOB
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

            // Execute and check for errors
            if ($stmt->execute()) {
                echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
            } else {
                $error = $stmt->errorInfo();
                echo "<script>alert('Failed to update profile: " . $error[2] . "');</script>";
            }
        }

        ?>

        <div class="mt-8 mb-8">
            <div>
                <form action="profile.php" method="POST" enctype="multipart/form-data" onsubmit="return saveProfile();">
                    <main class="mx-auto max-w-[1420px] px-4 sm:px-6 lg:px-8">
                        <div class="p-2 md:p-4 flex justify-center items-center">
                            <div class="px-6 pb-8 mt-20 sm:max-w-xl sm:rounded-lg bg-white">

                                <div class="flex items-center mt-6">
                                    <div class="">
                                        <a href="../in_web/book.php">
                                            <i class="fa-solid fa-arrow-left fa-lg"></i>
                                        </a>
                                    </div>
                                    <h2 class="pl-24 font-bold sm:text-2xl">Public Profile</h2>
                                </div>

                                <div class="grid max-w-2xl mx-auto mt-8">
                                    <div class="flex flex-col items-center space-y-5 sm:flex-row sm:space-y-0">

                                        <?php
                                        $profileImage = !empty($row['image']) ? 'data:image/jpeg;base64,' . base64_encode($row['image']) : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
                                        ?>

                                        <img class="object-cover w-40 h-40 p-1 rounded-full ring-2 ring-indigo-300 dark:ring-indigo-500"
                                            src="<?= $profileImage ?>"
                                            alt="avatar">

                                        <div class="flex flex-col space-y-5 sm:ml-8 items-center">
                                            <label class="flex flex-col items-center px-4 py-3.5 bg-[#202142] rounded-lg shadow-md tracking-wide uppercase border border-indigo-200 cursor-pointer hover:bg-indigo-900 text-indigo-100 hover:text-white focus:outline-none focus:ring-4 focus:ring-indigo-200">
                                                <span class="text-base leading-normal">Change Picture</span>
                                                <input type="file" name="profile_picture" class="hidden" onchange="displayFileName(this)">
                                            </label>
                                        </div>

                                    </div>

                                    <div class="items-center mt-8 sm:mt-14 text-[#202142]">

                                        <div
                                            class="flex flex-col items-center w-full mb-2 space-x-0 space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0 sm:mb-6">
                                            <div class="w-full">
                                                <label for="first_name"
                                                    class="block mb-2 text-sm font-medium text-black">
                                                    first name</label>
                                                <input type="text" id="first_name" name="first_name"
                                                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                                    placeholder="Your first name" value="<?= $row['first_name']; ?>">
                                            </div>

                                            <div class="w-full">
                                                <label for="last_name"
                                                    class="block mb-2 text-sm font-medium text-black">
                                                    last name</label>
                                                <input type="text" id="last_name" name="last_name"
                                                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                                    placeholder="Your last name" value="<?= $row['last_name']; ?>">
                                            </div>

                                        </div>

                                        <div
                                            class="flex flex-col items-center w-full mb-2 space-x-0 space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0 sm:mb-6">
                                            <div class="w-full">
                                                <label for="user_name"
                                                    class="block mb-2 text-sm font-medium text-black">
                                                    username</label>
                                                <input type="text" id="user_name" name="user_name"
                                                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                                    value="<?= $row['user_name']; ?>">
                                            </div>

                                            <div class="w-full">
                                                <label for="phone"
                                                    class="block mb-2 text-sm font-medium text-black">
                                                    phone</label>
                                                <input type="text" id="phone" name="phone"
                                                    class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                                    placeholder="Your phone" value="<?= $row['phone']; ?>">
                                            </div>

                                        </div>

                                        <div class="mb-2 sm:mb-6">
                                            <label for="email"
                                                class="block mb-2 text-sm font-medium text-black">
                                                email</label>
                                            <input type="email" id="email" name="email"
                                                class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                                placeholder="your.email@mail.com" value="<?= $row['email']; ?>">
                                        </div>

                                        <div class="mb-6">
                                            <label for="Address"
                                                class="block mb-2 text-sm font-medium text-black">Address</label>
                                            <textarea id="address" name="address" rows="4"
                                                class="block p-2.5 w-full text-sm text-indigo-900 bg-indigo-50 rounded-lg border border-indigo-300 focus:ring-indigo-500 focus:border-indigo-500 "
                                                placeholder="Write your address here..."><?= $row['address']; ?></textarea>
                                        </div>


                                        <div class="flex justify-end">
                                            <button type="submit" onclick="saveProfile()"
                                                class="text-white bg-indigo-700  hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">Save</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </form>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include('../main_design/footer.php') ?>

    <script>
        function saveProfile() {
            return confirm('Are you sure you want to save your profile?');
        }
    </script>

</body>

</html>