<?php

include_once "config/class.php";

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body class="bg-[#F2EBE3]">
  <div>
    <!-- nav bar -->
    <?php include('./main_design/nav.php'); ?>
    <!-- main detail -->
    <section>

      <?php

      // สร้างการเชื่อมต่อกับฐานข้อมูล
      $conn = new PDO("mysql:host=localhost;dbname=book_nest", "root", "");

      // กำหนด ID ของหนังสือที่ต้องการ
      $book_id = 1;

      // ดึงข้อมูลภาพจากฐานข้อมูล
      $sql = "SELECT image FROM book_info WHERE id = :id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $book_id);
      $stmt->execute();
      $imgData = $stmt->fetch(PDO::FETCH_ASSOC);

      // เข้ารหัสภาพเป็น Base64
      $imageBase64 = isset($imgData['image']) ? base64_encode($imgData['image']) : null;

      // กำหนดประเภทของภาพ (ตรวจสอบว่าเป็น jpeg, png หรือประเภทอื่น ๆ)
      $imageType = 'image/jpeg'; // เปลี่ยนตามประเภทที่ถูกต้อง

      ?>

      <div class="flex flex-col items-center justify-center lg:flex-row lg:justify-between px-36 py-48">
        <!-- left image -->
        <div class="lg:w-1/3 flex justify-center lg:mt-32">
          <img class="origin-bottom -rotate-12 lg:h-100 lg:w-80 shadow-2xl"
            src="data:<?php echo $imageType; ?>;base64,<?php echo $imageBase64; ?>"
            alt="Atomic Habits Book">
        </div>
        <!-- Text Section -->
        <div class="self-start mx-auto max-w-2xl">
          <div class="text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-7xl">
              Reading Mode is On. Enjoy!
            </h1>
            <p class="mt-14 text-2xl leading-8 text-gray-900">
              Today Is The Perfect Day To Immerse Yourself In A
              Book. Whether It's An Old Classic Or A New
              Discovery, Grab Your Book And Let Your
              Imagination Run Wild!
            </p>
            <div class="mt-14 flex items-center justify-center gap-x-6">
              <a href="./in_web/book.php"
                class="rounded-md bg-gray-800 px-40 py-3 text-2xl font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Go
                to shop</a>
            </div>
          </div>
        </div>
        <!-- Right Book Image -->
        <div class="lg:w-1/3 flex justify-center lg:mt-32">
          <img class="origin-bottom rotate-12 lg:h-100 lg:w-80 shadow-2xl" src="https://m.media-amazon.com/images/I/71PkUDFLDSL._SL1500_.jpg" alt="g">
        </div>
      </div>
    </section>
  </div>

  <!-- footer -->
  <?php include('./main_design/footer.php') ?>
</body>

</html>