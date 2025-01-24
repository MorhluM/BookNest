<?php

include '../config/connection.php';
$conn = connectDB();

if (!isset($_SESSION['userId'])) {

    header("Location: /booknest/in_web/login.php");
    exit();
}

$sql = $conn->prepare("SELECT * FROM users WHERE id = :id");
$sql->bindParam(':id', $_SESSION['userId'], PDO::PARAM_INT);
$sql->execute();
// Fetch the user data
$users = $sql->fetch();

$userId = $_SESSION['userId'];
$stmt = $conn->prepare("SELECT c.*, b.name, b.price, b.author, b.image FROM cart c JOIN book_info b ON c.book_id = b.id WHERE c.user_id = ?");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    // Redirect to the book listing page or display a message
    header("Location: /booknest/in_web/book.php"); // Redirecting to the book page
    exit();
}

$get = $conn->prepare("SELECT * FROM book_info");
$get->execute();
$books = $get->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

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
                        <div class="mr-5 text-xl font-semibold">
                            <a href="/booknest/in_web/shipping.php">
                                <h2>Shipping</h2>
                            </a>
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
                        <div class="mx-auto w-full flex-none lg:max-w-3xl pr-48">
                            <div class="space-y-6">
                                <!-- card product -->
                                <div class=" bg-white rounded-lg shadow-lg p-8">
                                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 mb-6">Your info</h1>

                                    <form action="payment.php" method="POST">
                                        <div>
                                            <div>
                                                <label for="price" class="block text-lg font-medium leading-6 text-gray-900 pl-1">Country</label>
                                                <div class="relative mt-2 rounded-md shadow-sm">
                                                    <label for="country" class="sr-only">Country</label>
                                                    <select id="country" name="country" class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                                                        <option value="" disabled selected>Select a country</option>
                                                        <option value="United States">United States</option>
                                                        <option value="Canada">Canada</option>
                                                        <option value="Europe">Europe</option>
                                                        <option value="Japan">Japan</option>
                                                        <option value="Australia">Australia</option>
                                                        <option value="Thailand">Thailand</option>
                                                        <option value="Singapore">Singapore</option>
                                                        <!-- Add more country options here as needed -->
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mt-6">
                                                <label for="address" class="block text-lg font-medium leading-6 text-gray-900 pl-1">address</label>
                                                <div class="relative mt-2 rounded-md shadow-sm">
                                                    <label for="address" class="sr-only">Address</label>
                                                    <input type="text" name="address" id="address" class="shadow-md block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600"
                                                        value="<?= $users['address'] ?>" placeholder="Enter country">
                                                </div>
                                            </div>

                                            <div class="mt-6">
                                                <?php
                                                $fullName = '';
                                                if ($users['first_name'] && $users['last_name'] != null) {
                                                    $fullName = $users['first_name'] . ' ' . $users['last_name'];
                                                }

                                                ?>
                                                <label for="fullname" class="block text-lg font-medium leading-6 text-gray-900 pl-1">First and Last Name</label>
                                                <div class="relative mt-2 rounded-md shadow-sm">
                                                    <label for="fullname" class="sr-only">fullname</label>
                                                    <input type="text" name="fullname" id="fullname" class="shadow-md block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600"
                                                        value="<?= $fullName ?>" placeholder="Enter fullname">
                                                </div>
                                            </div>

                                            <div class="mt-6">
                                                <label for="email" class="block text-lg font-medium leading-6 text-gray-900 pl-1">Email</label>
                                                <div class="relative mt-2 rounded-md shadow-sm">
                                                    <label for="email" class="sr-only">email</label>
                                                    <input type="text" name="email" id="email" class="shadow-md block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600" value="<?= $users['email'] ?>" placeholder="Enter email">
                                                </div>
                                            </div>

                                            <div class="mt-6">
                                                <label for="phone" class="block text-lg font-medium leading-6 text-gray-900 pl-1">Phone number</label>
                                                <div class="relative mt-2 rounded-md shadow-sm">
                                                    <label for="phone" class="sr-only">phone</label>
                                                    <input type="text" name="phone" id="phone" class="shadow-md block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600" value="<?= $users['phone'] ?>" placeholder="Enter phone number">
                                                </div>
                                            </div>

                                            <div class="mt-10">
                                                <button type="submit" class="flex w-full items-center justify-center rounded-lg px-5 py-2.5 text-lg font-medium text-white bg-gray-800">Save and continue</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">

                            <?php
                            $totalOrderValue = 0;

                            foreach ($cartItems as $item) {
                                $itemTotal = $item['price'] * $item['quantity']; // คำนวณราคาของรายการแต่ละรายการคูณกับจำนวน
                                $totalOrderValue += $itemTotal; // เพิ่มราคาของรายการเข้าไปในตัวแปรรวม
                            }

                            ?>

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
</body>

</html>