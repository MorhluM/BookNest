<?php

function searchBooks($conn, $searchTerm, $selectedCategories, $filterBrowse, $selectedRatings, $selectedPrices)
{
    // สร้าง SQL Query สำหรับค้นหาหนังสือ
    $sql = "SELECT b.* FROM book_info b";

    $params = [];

    if (!empty($selectedCategories)) {
        $sql .= " JOIN book_categories bc ON b.id = bc.book_id JOIN categories c ON bc.category_id = c.id";
        $categoryPlaceholders = implode(', ', array_fill(0, count($selectedCategories), '?'));
        $sql .= " WHERE c.name IN ($categoryPlaceholders)";
        $params = array_merge($params, $selectedCategories);
    }

    $sql .= empty($selectedCategories) ? " WHERE b.name LIKE ?" : " AND b.name LIKE ?";
    $params[] = '%' . $searchTerm . '%';

    // เพิ่มเงื่อนไขการกรองตามประเภท Best Seller หรือ New Release
    if (!empty($filterBrowse)) {
        if ($filterBrowse == 'best_seller') {
            $sql .= " AND b.is_best_seller = 1";
        } elseif ($filterBrowse == 'new_release') {
            $sql .= " AND b.is_new_release = 1";
        }
    }

    // กรองตาม Rating
    if (!empty($selectedRatings)) {

        $ratingConditions = [];
        foreach ($selectedRatings as $rating) {
            if ($rating == "5.0-4.6") {
                $ratingConditions[] = "b.rate BETWEEN 4.59 AND 5.0"; // นี่จะรวม 4.6
            } elseif ($rating == "4.5-3.4") {
                $ratingConditions[] = "b.rate BETWEEN 3.4 AND 4.58"; // นี่จะไม่รวม 4.5
            } elseif ($rating == "3.3-2.0") {
                $ratingConditions[] = "b.rate BETWEEN 2.0 AND 3.3"; // นี่จะไม่รวม 3.3
            }
        }
        $sql .= " AND (" . implode(' OR ', $ratingConditions) . ")";
    }

    // กรองตาม Price
    if (!empty($selectedPrices)) {
        $priceConditions = [];
        foreach ($selectedPrices as $price) {
            if ($price == "0-20") {
                $priceConditions[] = "b.price BETWEEN 0 AND 20";
            } elseif ($price == "20-50") {
                $priceConditions[] = "b.price BETWEEN 20 AND 50";
            } elseif ($price == "50+") {
                $priceConditions[] = "b.price > 50";
            }
        }
        $sql .= " AND (" . implode(' OR ', $priceConditions) . ")";
    }

    // เพิ่มเงื่อนไข GROUP BY และ HAVING เพื่อตรวจสอบว่าจำนวนหมวดหมู่ตรงกับจำนวนที่เลือก
    if (!empty($selectedCategories)) {
        $sql .= " GROUP BY b.id HAVING COUNT(c.id) = ?";
        $params[] = count($selectedCategories);
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $books = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['image'] = 'data:image/jpeg;base64,' . base64_encode($row['image']);
            $books[] = $row;
        }
    }

    $conn = null;
    return $books;
}
