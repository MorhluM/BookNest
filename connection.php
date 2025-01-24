<?php

$conn = mysqli_connect("localhost", "root", "", "book_nest");

if (!$conn) {
    die("Failed to connect database: " . mysqli_connect_error());
}
