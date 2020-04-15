<?php
include 'config/connection.php';
$emailforgot = $_POST["emailvalue"];
$password = $_POST['password'];
$confirmpassword = $_POST['confirmpassword'];
if ($password != $confirmpassword) {
?> <script>
        Swal.fire({
            icon: 'error',
            title: 'กรุณากรอกรหัสผ่านให้ตรงกัน..!',
        })
    </script><?php
            } else {
                // ------------ เช็คช่องเปลี่ยนรหัสผ่าน --------------
                $newencrypt = md5($confirmpassword);
                $pass_update = "UPDATE member SET password = '$newencrypt' WHERE email = '$emailforgot'";
                $pass_result = mysqli_query($conn, $pass_update);
            }
            if ($pass_result) {
                ?> <script>
        Swal.fire({
            icon: 'success',
            title: 'เปลี่ยนรหัสผ่านสำเร็จ..!',
            text: 'กรุณารอซักครู่ระบบจะพาท่านไปยังหน้าหลัก..',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=home");
            }
        });
    </script><?php
            } else {
                ?> <script>
        Swal.fire({
            icon: 'error',
            title: 'เปลี่ยนรหัสผ่านไม่สำเร็จ..!',
            text: 'กรุณาลองใหม่อีกครั้ง..',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=home");
            }
        });
    </script><?php
            }
                ?>