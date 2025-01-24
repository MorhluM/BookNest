<?php

include_once "config/class.php";

$web  = new Websystem();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body class="bg-[#F2EBE3]">

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success">
            <?php
            echo $_SESSION['success'];
            ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])) : ?>
        <div class="error">
            <?php
            echo $_SESSION['error'];
            ?>
        </div>
    <?php endif; ?>

    <!-- nav bar -->
    <?php include('../BookNest/main_design/nav.php'); ?>
    <!-- Sign box -->
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 sm:p-10 w-full max-w-lg">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                
                <h2 class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-800">Login</h2>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-sm">
                <form id="loginForm" action="./login_user/verify/login_verify.php" method="POST" class="space-y-6">
                    <div id="response" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative" role="alert">
                        Error: Something went wrong
                    </div>
                    <div>
                        <label for="user_name" class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="mt-1">
                            <input id="user_name" type="text" name="user_name" required class="block w-full rounded-lg border border-gray-300 py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" type="password" name="password" required class="block w-full rounded-lg border border-gray-300 py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400">
                        </div>
                        <div class="text-right mt-2">
                            <a href="#" class="text-sm text-blue-500 hover:text-blue-400">Forgot password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full rounded-lg bg-gray-800 py-2 text-white font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                            Sign in
                        </button>
                    </div>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">
                    Not a member? <a href="register.php" class="text-blue-500 hover:text-blue-400 font-semibold">Register</a>
                </p>
            </div>
        </div>
    </div>


</body>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
    $(document).ready(function() {
        $("#loginForm").on("submit", function(event) {
            event.preventDefault();

            const formData = {
                user_name: $("#user_name").val(),
                password: $("#password").val(),
            }

            $.ajax({
                url: "/booknest/api/loginUser.php",
                type: "POST",
                data: JSON.stringify(formData),
                contentType: "application/json",
                success: function(response) {
                    console.log(response);
                    if (response.success === true) {
                        if (response.user_level === 'a') {
                            window.location.href = "/booknest/admin_page/admin_dashboard.php";
                        } else {
                            window.location.href = "index.php"
                        }
                    } else {
                        $("#response").removeClass("alert-success").addClass("alert-danger").text(response.message).show();

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("An error occurred: " + textStatus);
                }
            })
        })
    })
</script>

</html>

<?php

if (isset($_SESSION['success']) || isset($_SESSION['error'])) {
    session_destroy();
}

?>