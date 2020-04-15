<?php
include 'config/connection.php';
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $companyname = $_POST['companyname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmpassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);
    if ($password != $confirmpassword) {
?> <script>
            Swal.fire({
                icon: 'error',
                title: 'กรุณากรอกรหัสผ่านให้ตรงกัน..!',
                showConfirmButton: false,
                timer: 2000
            }).then((result) => {
                if (result) {
                    window.location.replace("index.php?p=register");
                }
            });
        </script> <?php
                } else {
                    $user_check = "SELECT * FROM member WHERE username ='$_POST[username]'";
                    $result = mysqli_query($conn, $user_check);
                    $row = mysqli_num_rows($result);
                    ?>
        <?php
                    // ------------ เช็คชือผู้ใช้--------------
                    if ($row > 0) {
        ?> <script>
                Swal.fire({
                    icon: 'error',
                    title: 'ขออภัยชื่อผู้ใช้งานนี้ถูกใช้แล้ว..!',
                    showConfirmButton: false,
                    timer: 2500
                }).then((result) => {
                    if (result) {
                        window.location.replace("index.php?p=register");
                    }
                });
            </script> <?php
                    } else {
                        $encrypt = md5($password);
                        $query = "INSERT INTO member (username, password, fullname, company, address, email, phone_number, userlevel)
                VALUE ('$username', '$encrypt', '$fullname', '$companyname', '$address', '$email', '$phone', 'pending')";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                        ?> <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'ลงทะเบียนสำเร็จ..!',
                        text: 'กรุณารอสักครู่ผู้ดูแลระบบกำลังตรวจสอบและอนุมัติ..',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if (result) {
                            window.location.replace("index.php?p=home");
                        }
                    });
                </script> <?php
                        }
                    }
                }
            }
                            ?>