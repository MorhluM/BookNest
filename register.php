<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body class="bg-[#F2EBE3]">

    <?php include('../BookNest/main_design/nav.php'); ?>

    <!-- Register box -->
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 sm:p-10 w-full max-w-md">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <a href="index.php"><i class="fa-solid fa-arrow-right fa-rotate-180"></i></a>
                
                <h2 class="mt-6 text-center text-2xl font-bold text-gray-800">Register</h2>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-sm">
                <form id="registerForm" class="space-y-6">
                    <div id="response" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative" role="alert">
                        Error: Something went wrong
                    </div>

                    <div>
                        <label for="user_name" class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="mt-1">
                            <input type="text" id="user_name" name="user_name" placeholder="Enter your username" required class="block w-full rounded-lg border border-gray-300 py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <input type="email" id="email" name="email" placeholder="Enter your email" required class="block w-full rounded-lg border border-gray-300 py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input type="password" id="password" name="password" placeholder="Enter your password" required class="block w-full rounded-lg border border-gray-300 py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full rounded-lg bg-gray-800 py-2 text-white font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                            Sign up
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $("#registerForm").on("submit", function(event) {
                event.preventDefault();

                const formData = {
                    user_name: $("#user_name").val(),
                    email: $("#email").val(),
                    password: $("#password").val(),
                }

                $.ajax({
                    url: "/booknest/api/registerUser.php",
                    type: "POST",
                    data: JSON.stringify(formData),
                    contentType: "application/json",
                    success: function(response) {
                        console.log(response);
                        if (response.success === true) {
                            $("#response").removeClass("alert-danger").addClass("alert-success").text(response.message).show();
                            alert('Success! Your account has been created.');
                            window.location.href = 'index.php'
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

</body>

</html>