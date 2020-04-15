<?php

$conn = mysqli_connect('localhost', 'root', '', 'informationsearch');

if (!$conn) {
    die('เชื่อมต่อฐานข้อมูลไม่สำเร็จ' . mysqli_error($conn));
}
