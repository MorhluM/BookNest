<?php
include_once '../config/connection.php';

if (!isset($_SESSION['userId'])) {

    header("Location: /booknest/in_web/login.php");
    exit();
}

$conn = connectDB();
$userId = $_SESSION['userId']; // ตรวจสอบให้แน่ใจว่าได้เริ่ม session

$stmt = $conn->prepare("SELECT c.*, b.name, b.price, b.author, b.image FROM cart c JOIN book_info b ON c.book_id = b.id WHERE c.user_id = ?");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

$total = 0;

$sql = $conn->prepare("SELECT * FROM book_info");
$sql->execute();
$books = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 mb-6">Carts</h1>
                    </div>
                    <div class="flex">
                        <div class="mr-5 text-xl font-semibold">
                            <a href="/booknest/in_web/shopping_cart.php">
                                <h2>Shipping cart</h2>
                            </a>
                        </div>
                        <div class="mr-5 mt-1">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                        <div class="mr-5 text-xl">
                            <h2>Shipping</h2>
                        </div>
                        <div class="mr-5 mt-1">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                        <div class="mr-5 text-xl">
                            <h2>Payment</h2>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start lg:gap-40">
                        <!-- box product cart -->
                        <div class="mx-auto w-full flex-none lg:max-w-3xl">
                            <div class="space-y-6">

                                <!-- card product -->

                                <?php

                                $totalOrderValue = 0;

                                foreach ($cartItems as $item) :
                                    $itemTotal = $item['price'] * $item['quantity']; // คำนวณราคาของรายการแต่ละรายการคูณกับจำนวน
                                    $totalOrderValue += $itemTotal; // เพิ่มราคาของรายการเข้าไปในตัวแปรรวม

                                ?>
                                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm  md:p-6">
                                        <div class="space-y-4 md:gap-6 md:space-y-0">
                                            <div class="flex">
                                                <img class="w-44 object-scale-down transition-opacity duration-200 group-hover:opacity-75" src="data:image/jpeg;base64,<?= base64_encode($item['image']) ?>" alt="imac image" />
                                                <div class="flex flex-1 flex-col ml-4">
                                                    <h3 class="text-xl font-semibold text-black"><?= $item['name'] ?></h3>
                                                    <h3 class="text-base font-medium text-gray-700">By <?= $item['author'] ?></h3>
                                                    <div class="items-center justify-between mt-32">
                                                        <h3 class="text-2xl font-medium text-gray-700">$<?= $item['total'] ?></h3>
                                                        <a href="#" onclick="removeItem(<?= $item['id'] ?>)" class="flex w-44 items-center justify-center rounded-lg px-5 py-1.5 text-base font-medium text-black border-solid border-2 mt-2">Remove</a>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <h3 class="text-base font-medium text-gray-700">Quantity</h3>
                                                    <div class="flex mt-2">
                                                        <!-- ปุ่มสำหรับลบจำนวน -->
                                                        <button onclick="updateQuantity(<?= $item['id'] ?>, -1)" class="group rounded-l-lg px-2 py-[12px] border border-gray-200 flex items-center justify-center shadow-sm shadow-transparent transition-all duration-500 hover:bg-gray-50 hover:border-gray-300 hover:shadow-gray-300 focus-within:outline-gray-300">
                                                            <i class="fa-solid fa-minus"></i>
                                                        </button>
                                                        <input type="text"
                                                            class="border-y border-gray-200 outline-none text-gray-900 font-semibold text-lg w-full max-w-[44px] placeholder:text-gray-900 text-center bg-transparent"
                                                            value="<?= $item['quantity'] ?>">
                                                        <button
                                                            onclick="updateQuantity(<?= $item['id'] ?>, 1)" class="group rounded-r-lg px-2 py-[12px] border border-gray-200 flex items-center justify-center shadow-sm shadow-transparent transition-all duration-500 hover:bg-gray-50 hover:border-gray-300 hover:shadow-gray-300 focus-within:outline-gray-300">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>

                        </div>

                        <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                            <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm  sm:p-6">
                                <p class="text-xl text-center font-semibold text-black">Order summary</p>

                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <dl class="flex items-center justify-between gap-4">
                                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Order Value</dt>
                                            <dd class="text-base font-medium text-black">$<?= number_format($totalOrderValue, 2) ?></dd>
                                        </dl>

                                        <dl class="flex items-center justify-between gap-4">
                                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Delivery</dt>
                                            <dd class="text-base font-medium text-black">Free shipping</dd>
                                        </dl>
                                    </div>

                                    <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                        <dt class="text-base font-bold text-black">Total</dt>
                                        <dd class="text-base font-bold text-black">$<?= number_format($totalOrderValue, 2) ?></dd>
                                    </dl>
                                </div>
                            </div>

                            <a href="shipping.php" class="flex w-full items-center justify-center rounded-lg px-5 py-2.5 text-lg font-medium text-white bg-gray-800">Proceed to Checkout</a>

                        </div>

                    </div>

                    <!-- reccoment -->

                    <div class="hidden xl:mt-36 xl:block">
                        <h3 class="text-3xl font-semibold text-center text-black">Recomendation</h3>
                        <div class="mt-6 grid grid-cols-5 gap-8 sm:mt-8">

                            <!-- card -->
                            <?php $randomKeys = array_rand($books, min(5, count($books))); ?>
                            <?php foreach ($randomKeys as $key):
                                $book = $books[$key];
                            ?>
                                <div class="group relative bg-white inline-block rounded-lg overflow-hidden shadow-md p-3">
                                    <a href="book_overview.php?id=<?= $book['id'] ?>">
                                        <img src="data:image/jpeg;base64,<?= base64_encode($book['image']) ?>" alt="<?= $book['name'] ?>" class="w-full object-scale-down transition-opacity duration-200 group-hover:opacity-75" />
                                    </a>
                                    <div class="mt-4">
                                        <div>
                                            <div class="flex items-center">
                                                <h3 class="text-xl font-semibold text-gray-700"><?= $book['name'] ?></h3>
                                                <!-- <button type="button" class="ml-auto inline-flex rounded-full p-2 text-gray-400 hover:text-gray-500" aria-label="Add to favorites">
                                                    <i class="fa-regular fa-heart"></i>
                                                </button> -->
                                            </div>
                                            <h3 class="text-lg text-gray-700">by <?= $book['author'] ?></h3>
                                            <div class="flex items-center text-base text-gray-700">
                                                <i class="fa-solid fa-star text-yellow-400"></i>
                                                <span class="ml-1"><?= $book['rate'] ?></span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <p class="mt-1 text-lg font-medium text-gray-900">$<?= number_format($book['price'], 2) ?></p>

                                                <?php if (isset($_SESSION['userId'])) { ?>
                                                    <form action="../config/add_to_cart.php" method="POST">
                                                        <input type="hidden" name="userId" value="<?= $_SESSION['userId'] ?>">
                                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                                        <button type="submit" class="ml-auto inline-flex items-center justify-center rounded-md bg-gray-800 px-2 py-1 text-base font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">
                                                            Add cart
                                                        </button>
                                                    </form>
                                                <?php } else { ?>
                                                    <a href="/booknest/login.php">
                                                        <button type="submit" class="ml-auto inline-flex items-center justify-center rounded-md bg-gray-800 px-2 py-1 text-base font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">
                                                            Add cart
                                                        </button>
                                                    </a>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                        <a href="../in_web/book.php">

                            <div class="flex mt-10 text-xl justify-end">
                                <h3>View all</h3>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                </main>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include('../main_design/footer.php') ?>

    <script>
        function removeItem(bookId) {
            if (confirm('Are you sure you want to remove this item from the cart?')) {
                $.ajax({
                    url: 'remove_item.php',
                    type: 'POST',
                    data: {
                        id: bookId
                    },
                    success: function(response) {
                        console.log(response);
                        alert('Item removed from the cart successfully!');
                        location.reload(); // Refresh the page to update the cart
                    },
                    error: function() {
                        alert('Failed to remove the item from the cart.');
                    }
                });
            }
        }

        function updateQuantity(bookId, change) {
            $.ajax({
                url: 'update_quantity.php',
                type: 'POST',
                data: {
                    id: bookId,
                    change: change
                },

                success: function(response) {
                    // Check if the response indicates that the quantity is zero
                    if (response.newQuantity === 0) {
                        removeItem(bookId); // Call the removeItem function to remove the item
                    } else {
                        location.reload(); // Refresh the page to update the cart
                    }
                },
                error: function() {
                    alert('Failed to update the quantity.');
                }
            });
        }
    </script>

</body>

</html>