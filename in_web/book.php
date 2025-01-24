<?php
include_once '../config/connection.php';
include_once '../config/search_book.php';

$conn = connectDB();

// Search books logic
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
$filterBrowse = isset($_GET['browse']) ? $_GET['browse'] : ''; // รับค่า best seller หรือ new release
$selectedRatings = isset($_GET['rating']) ? $_GET['rating'] : [];
$selectedPrices = isset($_GET['price']) ? $_GET['price'] : [];

$books = searchBooks($conn, $searchTerm, $selectedCategories, $filterBrowse, $selectedRatings, $selectedPrices);

$conn = null;
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
        <div class="mt-8">
            <div>
                <main class="mx-auto max-w-[1420px] px-4 sm:px-6 lg:px-8" x-data="{ open: false, filterOpen: [false, false] }">
                    <div class="flex items-baseline justify-between pt-24">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Books</h1>
                        <!-- Search -->
                        <form method="get" action="" class="flex items-center border-b border-gray-800">
                            <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search..." class="block w-full appearance-none bg-transparent py-4 pl-4 pr-12 text-base text-slate-900 placeholder:text-slate-600 focus:outline-none sm:text-sm sm:leading-6">
                            <button type="submit" class="ml-2 text-gray-600"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <section aria-labelledby="products-heading" class="pb-24 pt-6">
                        <h2 id="products-heading" class="sr-only">Products</h2>

                        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                            <!-- Filters -->
                            <form method="get" action="">
                                <h3 class="sr-only">Categories</h3>
                                <div class="py-6" x-data="{ open: false }">
                                    <h3 class="-my-3 flow-root border-b border-gray-800 ">
                                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between bg-[#F2EBE3] py-3 text-sm text-gray-400 hover:text-gray-500" aria-controls="filter-section-0" :aria-expanded="open">
                                            <span class="font-medium text-gray-900">Categories</span>
                                            <span class="ml-6 flex items-center ">
                                                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <div x-show="open" id="filter-section-0" class="pt-6" role="region" aria-labelledby="filter-heading-0">
                                        <div class="space-y-4">

                                            <?php
                                            $categories = ['fantasy', 'self-help', 'fiction', 'romance', 'mystery', 'history', 'manga', 'technology'];
                                            foreach ($categories as $category): ?>
                                                <div class="flex items-center">
                                                    <input id="filter-<?= $category ?>" name="categories[]" type="checkbox" value="<?= $category ?>" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                    <label for="filter-<?= $category ?>" class="ml-3 text-sm text-black"><?= ucfirst(str_replace('_', ' ', $category)) ?></label>
                                                </div>
                                            <?php endforeach; ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="py-6" x-data="{ open: false }">
                                    <h3 class="-my-3 flow-root border-b border-gray-800">
                                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between bg-[#F2EBE3] py-3 text-sm text-gray-400 hover:text-gray-500" aria-controls="filter-section-1" :aria-expanded="open">
                                            <span class="font-medium text-gray-900">Browse</span>
                                            <span class="ml-6 flex items-center">
                                                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <div x-show="open" id="filter-section-1" class="pt-6" role="region" aria-labelledby="filter-heading-1">
                                        <div class="space-y-4">

                                            <div class="flex items-center">
                                                <input id="filter-best_seller" name="browse" type="checkbox" value="best_seller" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label for="filter-best_seller" class="ml-3 text-sm text-gray-600">Best seller</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-new_release" name="browse" type="checkbox" value="new_release" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label for="filter-new_release" class="ml-3 text-sm text-gray-600">New release</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="py-6" x-data="{ open: false }">
                                    <h3 class="-my-3 flow-root border-b border-gray-800">
                                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between bg-[#F2EBE3] py-3 text-sm text-gray-400 hover:text-gray-500" aria-controls="filter-section-1" :aria-expanded="open">
                                            <span class="font-medium text-gray-900">Rating</span>
                                            <span class="ml-6 flex items-center">
                                                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <div x-show="open" id="filter-section-1" class="pt-6" role="region" aria-labelledby="filter-heading-1">
                                        <div class="space-y-4">
                                            <div class="flex items-center">
                                                <input id="filter-rate_high" name="rating[]" type="checkbox" value="5.0-4.6" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label for="filter-rate_high" class="ml-3 text-sm text-gray-600"><i class="fa-solid fa-star text-yellow-400"></i> 5.0-4.6</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-rate_medium" name="rating[]" type="checkbox" value="4.5-3.4" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label for="filter-rate_medium" class="ml-3 text-sm text-gray-600"><i class="fa-solid fa-star text-yellow-400"></i> 4.5-3.4</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-rate_low" name="rating[]" type="checkbox" value="3.3-2.0" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label for="filter-rate_low" class="ml-3 text-sm text-gray-600"><i class="fa-solid fa-star text-yellow-400"></i> 3.3-2.0</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-6" x-data="{ open: false }">
                                    <h3 class="-my-3 flow-root border-b border-gray-800">
                                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between bg-[#F2EBE3] py-3 text-sm text-gray-400 hover:text-gray-500" aria-controls="filter-section-1" :aria-expanded="open">
                                            <span class="font-medium text-gray-900">Price</span>
                                            <span class="ml-6 flex items-center">
                                                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                                <svg x-show="open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <div x-show="open" id="filter-section-1" class="pt-6" role="region" aria-labelledby="filter-heading-1">
                                        <div class="space-y-4">
                                            <div class="flex items-center">
                                                <input id="filter-price_cheap" name="price[]" type="checkbox" value="0-20" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label for="filter-price_cheap" class="ml-3 text-sm text-gray-600">0-20$</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-price_medium" name="price[]" type="checkbox" value="20-50" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label for="filter-price_medium" class="ml-3 text-sm text-gray-600">20-50$</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-price_high" name="price[]" type="checkbox" value="50+" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label for="filter-price_high" class="ml-3 text-sm text-gray-600">50$ +</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-between py-6">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-base font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">Apply Filter</button>
                                    <button type="reset" class="inline-flex items-center justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-base font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">
                                        <a href="?">Reset</a>
                                    </button>
                                </div>

                            </form>

                            <!-- Product Grid 1-->
                            <div class="lg:col-span-3">
                                <div class="grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                                    <!-- Product Card -->

                                    <?php foreach ($books as $book): ?>
                                        <div class="group relative bg-white inline-block rounded-lg overflow-hidden shadow-md p-3">
                                            <a href="book_overview.php?id=<?= $book['id'] ?>">
                                                <img src="<?= $book['image'] ?>" alt="<?= $book['name'] ?>" class="w-full object-scale-down transition-opacity duration-200 group-hover:opacity-75" />
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
                            </div>
                        </div>
                    </section>
                </main>
                <!-- more detail -->
            </div>
        </div>
    </div>
    <!-- Footer detail -->
    <?php include('../main_design/footer.php') ?>

</body>

</html>