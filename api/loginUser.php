<?php
header("Content-Type: application/json");


$input = file_get_contents("php://input");

$data = json_decode($input, true);

include_once "../config/class.php";

if (isset($_SERVER["REQUEST_METHOD"]) == "POST") {
    $user_name = trim($data['user_name']);
    $password = trim($data['password']);

    if (empty($user_name) || empty($password)) {
        echo json_encode([
            'message' => 'All fields are required.',
            'status' => 400,
            'success' => false
        ]);
    } else {
        $web = new Websystem();
        $web->loginUser($user_name, $password);
    }
}

?>