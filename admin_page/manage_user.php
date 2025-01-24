<?php

include_once '../config/connection.php';

$conn = connectDB();

$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</head>

<body class="sb-nav-fixed">

    <!-- nav -->
    <?php

    include('nav.php');

    ?>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <!-- Include your Sidenav here -->
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="admin_dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Book management
                        </a>



                        <div class="sb-sidenav-menu-heading">Options</div>
                        <a class="nav-link" href="manage_user.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Manage Users
                        </a>

                    </div>
                </div>
            </nav>

        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Manage Users</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Book management</a></li>
                        <li class="breadcrumb-item active">Manage Users</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Users List
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>

                                        <th>Email</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($result)) {
                                        foreach ($result as $row) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["user_name"]) . "</td>";

                                            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                                            
                                            $imageSrc = !empty($row['image']) ? 'data:image/jpeg;base64,' . base64_encode($row['image']) : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
                                            echo "<td><img src='$imageSrc' width='50' height='50'/></td>";

                                            echo "<td>";
                                            echo "<a href='edit_users.php?id=" . $row["id"] . "'><button onclick='editUser(" . $row["id"] . ")' class='btn btn-primary'>Edit</button></a>";
                                            echo " ";
                                            echo "<button onclick='deleteUser(" . $row["id"] . ")' class='btn btn-danger'>Delete</button>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='11'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        function editUser(id) {
            window.location.href = 'edit_user.php?id=' + id; // เปลี่ยนไปที่หน้าสำหรับแก้ไขข้อมูลผู้ใช้
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                // ลบผู้ใช้ตาม id
                // ส่งคำขอ AJAX เพื่อลบผู้ใช้
                $.ajax({
                    url: '/booknest/admin_page/deleteUser.php',
                    type: 'POST',
                    data: JSON.stringify({
                        id: id
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        console.log(response);
                        try {
                            response = JSON.parse(response); // แปลง JSON ถ้าจำเป็น
                        } catch (e) {
                            alert('Unexpected response format');
                            return;
                        }

                        if (response.success) {
                            alert('User deleted successfully');
                            location.reload(); // โหลดหน้าใหม่เพื่อแสดงการเปลี่ยนแปลง
                        } else {
                            alert('Error deleting user: ' + response.message);
                            location.reload();
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>