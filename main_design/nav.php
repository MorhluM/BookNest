<header class="absolute inset-x-0 top-3 z-50">
    <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div class="flex lg:flex-1">
            <a href="/booknest/index.php" class="-m-1.5 p-1.5 ml-4">
                <h3 class=" font-semibold text-xl">Booknest.</h3>
            </a>
        </div>
        <div class="hidden lg:flex lg:gap-x-12 text">
            <a href="/booknest/index.php" class="text-xl font-semibold leading-6 text-gray-900">Home</a>
            <a href="/booknest/in_web/book.php" class="text-xl font-semibold leading-6 text-gray-900">Shop</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            <?php if (isset($_SESSION['userId'])) { ?>
                <!-- fav icon -->
                <!-- <button type="button" class="rounded-full p-2 text-black hover:text-gray-500" aria-label="Add to favorites">
                    <i class="fa-regular fa-heart"></i>
                </button> -->
                <button type="button" class="rounded-full p-2 text-black hover:text-gray-500" aria-label="Add to Cart">
                    <a href="/booknest/in_web/shopping_cart.php">
                        <i class="fa-solid fa-cart-shopping">

                        </i>
                    </a>
                </button>
                <div class="p-1.5 font-bold text-black">
                    <span>|</span>
                </div>
                <div x-data="{ open: false }">
                    <div class="relative inline-block">
                        <div>
                            <button type="button" class="rounded-full p-2 text-black hover:text-gray-500" aria-label="Add to favorites" @click="open = !open">
                                <i class="fa-regular fa-user"></i>
                            </button>

                        </div>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                <a href="/booknest/in_web/profile.php" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">Account settings</a>
                                <form method="POST" action="/booknest/main_design/logout.php" role="none">
                                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-3">Sign out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="flex">
                    <div class="mr-5 p-1">
                        <a href="/booknest/login.php">
                            <h3 class=" font-semibold text-xl">Login</h3>
                        </a>
                    </div>
                    <div class="ml-4 mr-4 rounded-lg bg-gray-800 hover:bg-gray-900 py-1 text-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 p-1 px-2 shadow-sm">
                        <a href="/booknest/register.php">
                            <h3 class=" font-semibold text-xl">Register</h3>
                        </a>
                    </div>
                </div>
            <?php } ?>


        </div>
    </nav>
</header>