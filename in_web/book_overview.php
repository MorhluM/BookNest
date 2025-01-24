<?php
include_once '../config/connection.php';

$conn = connectDB();

$sql = $conn->prepare("SELECT * FROM book_info");
$sql->execute();
$run = $sql->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    // Prepare and execute the statement to fetch book details
    $stmt = $conn->prepare("SELECT * FROM book_info WHERE id = :id");
    $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        echo "Book not found.";
        exit;
    }

    $conn = null;
} else {
    echo "No book selected.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>

<body class="bg-[#F2EBE3]" x-data="{ isOpen: false }">
    <div>
        <!-- nav bar -->
        <?php include('../main_design/nav.php'); ?>
        <div>
            <div class="mx-auto max-w-[1000px] px-4 sm:px-6 lg:px-8">

                <div>
                    <div class="text-center mt-40 pb-14">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Books</h1>
                    </div>
                </div>

                <div class="font-sans p-4 tracking-wide  max-lg:max-w-2xl mx-auto border-gray-200 bg-white rounded-lg">
                    <div class="items-start ">
                        <div>
                            <div class=" font-semibold text-xl pb-6">
                                <a href="../in_web/book.php">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Back
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-10">
                                <div>
                                    <div class="p-4 items-center sm:h-[420px]">

                                        <?php
                                        echo '<img src="data:image/jpeg;base64,' . base64_encode($book['image']) . '" class="w-full max-h-full object-contain object-top" />'
                                        ?>

                                    </div>
                                </div>
                                <div class="mt=0">
                                    <div class="flex justify-between">
                                        <h2 class="text-4xl font-extrabold text-gray-800"><?= htmlspecialchars($book['name']) ?></h2>
                                        <p class="text-gray-800 text-3xl font-bold">$<?= htmlspecialchars($book['price']) ?></p>
                                    </div>
                                    <h2 class="text-xl font-medium text-gray-800 mt-3">By <?= htmlspecialchars($book['author']) ?></h2>

                                    <div class="flex space-x-1 mt-4">
                                        <svg class="w-5 fill-yellow-400" viewBox="0 0 14 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                        </svg>
                                        <svg class="w-5 fill-yellow-400" viewBox="0 0 14 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                        </svg>
                                        <svg class="w-5 fill-yellow-400" viewBox="0 0 14 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                        </svg>
                                        <svg class="w-5 fill-yellow-400" viewBox="0 0 14 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                        </svg>
                                        <svg class="w-5 fill-[#CED5D8]" viewBox="0 0 14 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z" />
                                        </svg>

                                        <button type="button" class="px-2.5 py-1.5 bg-gray-100 text-xs text-gray-800 rounded flex items-center !ml-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 mr-1" fill="currentColor" viewBox="0 0 32 32">
                                                <path d="M14.236 21.954h-3.6c-.91 0-1.65-.74-1.65-1.65v-7.201c0-.91.74-1.65 1.65-1.65h3.6a.75.75 0 0 1 .75.75v9.001a.75.75 0 0 1-.75.75zm-3.6-9.001a.15.15 0 0 0-.15.15v7.2a.15.15 0 0 0 .15.151h2.85v-7.501z" data-original="#000000" />
                                                <path d="M20.52 21.954h-6.284a.75.75 0 0 1-.75-.75v-9.001c0-.257.132-.495.348-.633.017-.011 1.717-1.118 2.037-3.25.18-1.184 1.118-2.089 2.28-2.201a2.557 2.557 0 0 1 2.17.868c.489.56.71 1.305.609 2.042a9.468 9.468 0 0 1-.678 2.424h.943a2.56 2.56 0 0 1 1.918.862c.483.547.708 1.279.617 2.006l-.675 5.401a2.565 2.565 0 0 1-2.535 2.232zm-5.534-1.5h5.533a1.06 1.06 0 0 0 1.048-.922l.675-5.397a1.046 1.046 0 0 0-1.047-1.182h-2.16a.751.751 0 0 1-.648-1.13 8.147 8.147 0 0 0 1.057-3 1.059 1.059 0 0 0-.254-.852 1.057 1.057 0 0 0-.795-.365c-.577.052-.964.435-1.04.938-.326 2.163-1.71 3.507-2.369 4.036v7.874z" data-original="#000000" />
                                                <path d="M4 31.75a.75.75 0 0 1-.612-1.184c1.014-1.428 1.643-2.999 1.869-4.667.032-.241.055-.485.07-.719A14.701 14.701 0 0 1 1.25 15C1.25 6.867 7.867.25 16 .25S30.75 6.867 30.75 15 24.133 29.75 16 29.75a14.57 14.57 0 0 1-5.594-1.101c-2.179 2.045-4.61 2.81-6.281 3.09A.774.774 0 0 1 4 31.75zm12-30C8.694 1.75 2.75 7.694 2.75 15c0 3.52 1.375 6.845 3.872 9.362a.75.75 0 0 1 .217.55c-.01.373-.042.78-.095 1.186A11.715 11.715 0 0 1 5.58 29.83a10.387 10.387 0 0 0 3.898-2.37l.231-.23a.75.75 0 0 1 .84-.153A13.072 13.072 0 0 0 16 28.25c7.306 0 13.25-5.944 13.25-13.25S23.306 1.75 16 1.75z" data-original="#000000" />
                                            </svg>
                                            87 Reviews
                                        </button>
                                    </div>

                                    <p class="text-xl font-normal text-gray-800 my-8"><?= htmlspecialchars($book['short_description']) ?></p>

                                    <?php if (isset($_SESSION['userId'])) { ?>
                                        <div class="flex flex-wrap gap-4">

                                            <form action="../config/checkout.php" method="POST">
                                                <input type="hidden" name="userId" value="<?= $_SESSION['userId'] ?>">
                                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                                <button type="submit" class="min-w-[200px] px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded">Buy now</button>
                                            </form>

                                            <form action="../config/add_to_cart.php" method="POST">
                                                <input type="hidden" name="userId" value="<?= $_SESSION['userId'] ?>">
                                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                                <button type="submit" class="min-w-[200px] px-4 py-2.5 border border-gray-800 bg-transparent hover:bg-gray-50 text-gray-800 text-sm font-semibold rounded">
                                                    Add cart
                                                </button>
                                            </form>
                                        </div>
                                    <?php } else { ?>
                                        <div class="flex flex-wrap gap-4">

                                            <a href="/booknest/login.php">
                                                <button type="button" class="min-w-[200px] px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded">Buy now</button>
                                            </a>

                                            <a href="/booknest/login.php">
                                                <button type="submit" class="min-w-[200px] px-4 py-2.5 border border-gray-800 bg-transparent hover:bg-gray-50 text-gray-800 text-sm font-semibold rounded">
                                                    Add cart
                                                </button>
                                            </a>
                                        </div>
                                    <?php } ?>

                                </div>

                                <!-- <div class="space-y-4">
                                    <div class="bg-gray-100 p-4 flex items-center rounded sm:h-[182px]">
                                        <img src="https://readymadeui.com/images/product12.webp" alt="Product" class="w-full max-h-full object-contain object-top" />
                                    </div>
                                </div> -->

                            </div>

                            <div class="mt-8">
                                <ul class="flex border-b">
                                    <li
                                        class="text-gray-800 font-bold text-sm bg-gray-100 py-3 px-8 border-b-2 border-gray-800 cursor-pointer transition-all">
                                        Description</li>
                                </ul>

                                <div class="mt-8">
                                    <h3 class="text-lg font-bold text-gray-800">Description</h3>
                                    <p class="text-sm text-gray-600 mt-4"><?= htmlspecialchars($book['long_description']) ?></p>
                                </div>

                            </div>
                        </div>

                        <!-- asd -->

                    </div>
                </div>

                <!-- Recommentation -->
                <div class="hidden xl:mt-36 xl:block">
                    <h3 class="text-3xl font-semibold text-center text-black">Recomendation</h3>
                    <div class="mt-6 grid grid-cols-4 gap-8 sm:mt-8">

                        <!-- card -->
                        <?php $randomKeys = array_rand($run, min(4, (count($run)))); ?>
                        <?php foreach ($randomKeys as $key):
                            $runs = $run[$key];
                        ?>
                            <div class="group relative bg-white inline-block rounded-lg overflow-hidden shadow-md p-3">
                                <a href="book_overview.php?id=<?= $runs['id'] ?>">
                                    <img src="data:image/jpeg;base64,<?= base64_encode($runs['image']) ?>" alt="<?= $runs['name'] ?>" class="w-full object-scale-down transition-opacity duration-200 group-hover:opacity-75" />
                                </a>
                                <div class="mt-4">
                                    <div>
                                        <div class="flex items-center">
                                            <h3 class="text-xl font-semibold text-gray-700"><?= $runs['name'] ?></h3>
                                            <button type="button" class="ml-auto inline-flex rounded-full p-2 text-gray-400 hover:text-gray-500" aria-label="Add to favorites">
                                                <i class="fa-regular fa-heart"></i>
                                            </button>
                                        </div>
                                        <h3 class="text-lg text-gray-700">by <?= $runs['author'] ?></h3>
                                        <div class="flex items-center text-base text-gray-700">
                                            <i class="fa-solid fa-star text-yellow-400"></i>
                                            <span class="ml-1"><?= $runs['rate'] ?></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="mt-1 text-lg font-medium text-gray-900">$<?= number_format($runs['price'], 2) ?></p>

                                            <?php if (isset($_SESSION['userId'])) { ?>
                                                <form action="../config/add_to_cart.php" method="POST">
                                                    <input type="hidden" name="userId" value="<?= $_SESSION['userId'] ?>">
                                                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                                    <button type="submit" class="ml-auto inline-flex items-center justify-center rounded-md bg-gray-800 px-2 py-1 text-base font-medium text-white hover:gray-900 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">
                                                        Add cart
                                                    </button>
                                                </form>
                                            <?php } else { ?>
                                                <a href="/booknest/login.php">
                                                    <button type="submit" class="ml-auto inline-flex items-center justify-center rounded-md bg-gray-800 px-2 py-1 text-base font-medium text-white hover:gray-900 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">
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

            </div>
        </div>
    </div>
    <!-- Footer detail -->
    <?php include('../main_design/footer.php') ?>

</body>

</html>