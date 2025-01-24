<?php
require_once("../in_web/lib/PromptPayQR.php");

$PromptPayQR = new PromptPayQR(); // new object

$PromptPayQR->size = 4; // Set QR code size to 8
$PromptPayQR->id = '0909518382'; // PromptPay ID
$PromptPayQR->amount = 200.25; // Set amount (not necessary)
echo '<img src="' . $PromptPayQR->generate() . '" />';