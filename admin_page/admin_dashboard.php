<?php

include_once '../config/connection.php';

$conn = connectDB();

$sql = "SELECT * FROM book_info";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
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
                <div class="container-fluid px-4B">
                    <h1 class="mt-4">Book management</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Book management</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Books List
                            <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addBookModal">Add Book</button>
                        </div>

                        <!-- Modal for Adding Book -->
                        <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addBookModalLabel">Add Book</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addBookForm" action="add_book.php" method="POST" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Book Name</label>
                                                <input type="text" class="form-control" id="name" name="name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="author" class="form-label">Author</label>
                                                <input type="text" class="form-control" id="author" name="author" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Price</label>
                                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="rate" class="form-label">Rate</label>
                                                <input type="number" class="form-control" id="rate" name="rate" min="1" max="5" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="categories" class="form-label">Categories</label>
                                                <input type="text" class="form-control" id="categories" name="categories" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Image</label>
                                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="is_best_seller" name="is_best_seller">
                                                <label class="form-check-label" for="is_best_seller">Best Seller</label>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="is_new_release" name="is_new_release">
                                                <label class="form-check-label" for="is_new_release">New Release</label>
                                            </div>
                                            <button onclick="addBook()" type="submit" class="btn btn-primary">Add Book</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Author</th>
                                        <th>Price</th>
                                        <th>Rate</th>
                                        <th>Categories</th>
                                        <th>Image</th>
                                        <th>Best Seller</th>
                                        <th>New Release</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($result)) {
                                        foreach ($result as $row) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["author"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["rate"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["categories"]) . "</td>";
                                            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row["image"]) . "' width='50' height='50'/></td>";

                                            echo "<td>" . ($row["is_best_seller"] ? "Yes" : "No") . "</td>";
                                            echo "<td>" . ($row["is_new_release"] ? "Yes" : "No") . "</td>";


                                            echo "<td>";
                                            echo "<a href='edit_book.php?id=" . $row["id"] . "'><button onclick='editBook(" . $row["id"] . ")' class='btn btn-primary'>Edit</button></a>";
                                            echo " ";
                                            echo "<button onclick='deleteBook(" . $row["id"] . ")' class='btn btn-danger'>Delete</button>";
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
                            <a href="#">Terms &amp; Conditions</a>
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
        function addBook() {
            // ปิดการส่งฟอร์มเริ่มต้น
            event.preventDefault();

            const formData = new FormData(document.querySelector('#addBookForm'));

            fetch('add_book.php', { // ใช้เส้นทางที่ถูกต้อง
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message); // แสดงข้อความสำเร็จ
                        location.reload(); // โหลดหน้าใหม่เพื่อแสดงการเปลี่ยนแปลง
                    } else {
                        alert(data.message); // แสดงข้อความข้อผิดพลาด
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the book.');
                });
        }


        function editBook(id) {
            window.location.href = 'edit_book.php?id=' + id; // เปลี่ยนไปที่หน้าสำหรับแก้ไขหนังสือ
        }

        function deleteBook(id) {
            if (confirm('Are you sure you want to delete this book?')) {
                // ลบหนังสือตาม id
                // ส่งคำขอ AJAX เพื่อลบหนังสือ
                $.ajax({
                    url: '/booknest/admin_page/deleteBook.php',
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
                            alert('Book deleted successfully');
                            location.reload(); // โหลดหน้าใหม่เพื่อแสดงการเปลี่ยนแปลง
                        } else {
                            alert('Error deleting book: ' + response.message);
                            location.reload();
                        }

                    }
                });
            }
        }
    </script>
</body>

</html>