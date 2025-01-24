<?php

    include_once "connection.php";

    class Websystem {

        private $db;

        public function __construct(){
            $this->db = connectDB();
        }

        public function registerUser($user_name, $email, $password, $user_level = 'm') {
            try {

                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    echo json_encode([
                        'message' => 'Email already exists.',
                        'status' => 400,
                        'success' => false
                    ]);
                    return;
                }

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("INSERT INTO users(user_name, email, password, user_level) VALUES(:user_name, :email, :password, :user_level)");
                $stmt->execute([
                    'user_name' => $user_name,
                    'email' => $email,
                    'password' => $hashed_password,
                    'user_level' => $user_level
                ]);
                
                echo json_encode([
                    'message' => 'Registration successfully.',
                    'status' => 200,
                    'success' => true
                ]);
                
            } catch (PDOException $e) {
                echo json_encode([
                    'message' => 'A server error occured.',
                    'status' => 500,
                    'success' => false
                ]);
            }
        }

        public function loginUser($user_name, $password) {
            try {

                $stmt = $this->db->prepare("SELECT id, user_name, password, user_level FROM users WHERE user_name = :user_name");
                $stmt->execute(['user_name' => $user_name]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['userId'] = $user['id'];
                        $_SESSION['userLevel'] = $user['user_level'];
                        echo json_encode([
                            'message' => 'User login successfully.',
                            'status' => 200,
                            'success' => true,
                            'user_level' => $user['user_level']
                        ]);
                    } else {
                        echo json_encode([
                            'message' => 'Invalid username or password.',
                            'status' => 400,
                            'success' => false
                        ]);
                    }
                } else {
                    echo json_encode([
                        'message' => 'Username does not exist.',
                        'status' => 400,
                        'success' => false
                    ]);
                }

            } catch(PDOException $e) {
                echo json_encode([
                    'message' => 'A server error occured.',
                    'status' => 500,
                    'success' => false
                ]);
            }
        }

    }

?>