<?php

require_once("../in_web/lib/PromptPayQR.php");
include '../config/connection.php';

$conn = connectDB();

$stmt = $conn->prepare("SELECT c.*, b.name, b.price, b.author, b.image FROM cart c JOIN book_info b ON c.book_id = b.id WHERE c.user_id = ?");
$stmt->execute([$_SESSION['userId']]);
$cartItems = $stmt->fetchAll();


if (!isset($_SESSION['userId'])) {

    header("Location: /booknest/in_web/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $country = $_POST['country'] ?? '';
    $address = $_POST['address'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
}


$PromptPayQR = new PromptPayQR(); // new object

$PromptPayQR->size = 4; // Set QR code size to 8
$PromptPayQR->id = '0909518382'; // PromptPay ID
// echo '<img src="' . $PromptPayQR->generate() . '" />';

?>

<!DOCTYPE html>
<htmr lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shopping</title>

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    </head>

    <body class="bg-[#F2EBE3]">
        <div>
            <!-- nav bar -->
            <?php include('../main_design/nav.php'); ?>
            <div class="mt-10">
                <div>
                    <main class="mx-auto max-w-[1420px] px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-center pt-24 items-center">
                            <h1 class="text-4xl font-bold tracking-tight text-gray-900 mb-6">Checkout</h1>
                        </div>
                        <div class="flex">
                            <div class="mr-5 text-xl">
                                <a href="/booknest/in_web/shopping_cart.php">
                                    <h2>Shipping cart</h2>
                                </a>
                            </div>
                            <div class="mr-5 mt-1">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                            <div class="mr-5 text-xl">
                                <a href="/booknest/in_web/shipping.php">
                                    <h2>Shipping</h2>
                                </a>
                            </div>
                            <div class="mr-5 mt-1">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                            <div class="mr-5 text-xl font-semibold">
                                <a href="/booknest/in_web/payment">
                                    <h2>Payment</h2>
                                </a>
                            </div>
                        </div>
                        <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start lg:gap-40">
                            <!-- box product cart -->
                            <div class="mx-auto w-full flex-none lg:max-w-3xl pr-12">
                                <div class="space-y-6">
                                    <!-- Your Info Card -->
                                    <div>
                                        <!-- <h1 class="text-4xl font-bold tracking-tight text-gray-900 mb-6">Your Info</h1> -->
                                        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-md">
                                            <div class="flex">
                                                <div class="flex flex-1 flex-col">
                                                    <h3 class="text-lg font-medium"><?= $fullname ?></h3>
                                                    <h3 class="text-lg font-medium mt-2"><?= $email ?></h3>
                                                    <h3 class="text-lg font-medium mt-2"><?= $country ?>, <?= $address ?></h3>
                                                    <h3 class="text-lg font-medium mt-2"><?= $phone ?></h3>
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    <a href="shipping.php" class="text-black hover:underline">
                                                        <h3 class="text-lg font-medium">Edit</h3>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 mt-8">Delivery & Payment</h1>
                                        <div class="flex flex-col md:flex-row gap-4 mt-6">
                                            <!-- Standard Delivery Card -->
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="delivery" value="standard" class="sr-only peer">
                                                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-md transition-transform duration-200 hover:shadow-lg peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500">
                                                    <h3 class="text-xl font-medium text-gray-800">Standard</h3>
                                                    <p class="text-gray-600 mt-2">4-10 business days</p>
                                                    <p class="text-gray-800 mt-2 font-semibold">Free</p>
                                                </div>
                                            </label>

                                            <!-- Express Delivery Card -->
                                            <label class="flex-1">
                                                
                                                
                                            </label>
                                        </div>

                                        <div class="mt-6">
                                            <div class="relative mt-2 rounded-md shadow-sm">
                                                <label for="street" class="sr-only">Street</label>
                                                <input type="text" name="street" id="street" class="shadow-md block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600" placeholder="Street">
                                            </div>
                                        </div>

                                        <div class="flex flex-col md:flex-row gap-3 mt-4">
                                            <div class="flex-1">
                                                <label for="building" class="sr-only">Building</label>
                                                <input type="text" name="building" id="building" class="shadow-md block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600" placeholder="Building">
                                            </div>
                                            <div class="flex-1">
                                                <label for="apartment" class="sr-only">Apartment</label>
                                                <input type="text" name="apartment" id="apartment" class="shadow-md block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600" placeholder="Apartment number">
                                            </div>
                                        </div>

                                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 mt-8">Payment Method</h1>
                                        <!-- Payment Method Section (Add your payment methods here) -->
                                        <div>
                                            <div class="flex items-center mb-4 mt-6">
                                                <input checked id="default-radio-2" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600  border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 ">
                                                <label for="default-radio-2" class="ms-2 font-medium text-black text-lg">Scan QR Code</label>
                                            </div>
                                            <div class="flex items-center mb-4 mt-6">
                                                <?php
                                                echo '<img src="' . $PromptPayQR->generate() . '" />';
                                                ?>
                                            </div>
                                        </div>

                                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 mt-8">Privacy Policy & Terms and Conditions</h1>
                                        <div class="mt-4">
                                            <div class="flex items-center">
                                                <input id="terms-conditions" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600" required>
                                                <label for="terms-conditions" class="ms-2 text-gray-700 text-sm">By placing an order, I agree with the terms of Privacy and Conditions.</label>
                                            </div>
                                        </div>

                                        <div class="mt-10">
                                            <form action="../in_web/clear_cart.php" method="POST">
                                                <input type="hidden" name="userId" value="<?= $_SESSION['userId'] ?>">
                                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                                <button type="submit" onclick="placeOrder()" id="placeOrderButton" class="flex w-full items-center justify-center rounded-lg px-5 py-2.5 text-lg font-medium text-white bg-gray-800" disabled>Place order</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <?php
                            $totalOrderValue = 0;

                            foreach ($cartItems as $item) {
                                $itemTotal = $item['price'] * $item['quantity']; // คำนวณราคาของรายการแต่ละรายการคูณกับจำนวน
                                $totalOrderValue += $itemTotal; // เพิ่มราคาของรายการเข้าไปในตัวแปรรวม
                            }
                            $PromptPayQR->amount = $item['price'] // Set amount (not necessary)

                            ?>
                            <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                                <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm  sm:p-6">
                                    <p class="text-xl text-center font-semibold text-black">Order summary</p>

                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <dl class="flex items-center justify-between gap-4">
                                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Order Value</dt>
                                                <dd class="text-base font-medium text-black">$<?= $totalOrderValue ?></dd>
                                            </dl>

                                            <dl class="flex items-center justify-between gap-4">
                                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Delivery</dt>
                                                <dd class="text-base font-medium text-black">Free shipping</dd>
                                            </dl>
                                        </div>

                                        <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                            <dt class="text-base font-bold text-black">Total</dt>
                                            <dd class="text-base font-bold text-black">$<?= $totalOrderValue ?></dd>
                                        </dl>
                                    </div>
                                </div>


                            </div>

                        </div>

                    </main>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include('../main_design/footer.php') ?>


        <script>
            // ฟังก์ชันตรวจสอบสถานะของ checkbox
            document.getElementById('terms-conditions').addEventListener('change', function() {
                document.getElementById('placeOrderButton').disabled = !this.checked;
            });

            function placeOrder() {
                alert('ทำรายการสำเร็จ');
                window.location.href = 'book.php'; // เปลี่ยนหน้าไปที่ payment.php หลังจากแจ้งเตือน
            }
        </script>
    </body>

</htmr>