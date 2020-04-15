<?php include 'config/connection.php';
if (isset($_SESSION['username'])) {
    $usernamelogin = $_SESSION['username'];
    $editquery = $conn->query("SELECT * FROM member WHERE username = '$usernamelogin'");
    $editprofile = mysqli_fetch_array($editquery);
}
if (isset($_POST['edit'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $companyname = $_POST['companyname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    if ($password != $confirmpassword) {
?> <script>
            Swal.fire({
                icon: 'error',
                title: 'กรุณากรอกรหัสผ่านให้ตรงกัน..!',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                if (result) {
                    window.location.replace("index.php?p=editprofile");
                }
            });
        </script><?php
                } else {
                    // ------------ เช็คช่องเปลี่ยนรหัสผ่าน --------------
                    if ($password == '') {
                        $user_update = "UPDATE member SET fullname ='$fullname', company ='$companyname',
                        address ='$address', email ='$email', phone_number ='$phone' WHERE username = '$username'";
                        $edit_result = mysqli_query($conn, $user_update);
                    } else {
                        $newencrypt = md5($confirmpassword);
                        $user_update = "UPDATE member SET fullname = '$fullname', company = '$companyname',
                        address = '$address', email = '$email', phone_number = '$phone', password = '$newencrypt' WHERE username = '$username'";
                        $edit_result = mysqli_query($conn, $user_update);
                    }
                    if ($edit_result) {
                    ?> <script>
                Swal.fire({
                    icon: 'success',
                    title: 'แก้ไขข้อมูลสำเร็จ..!',
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
                    title: 'แก้ไขไม่สำเร็จ..!',
                    showConfirmButton: false,
                    timer: 2500
                }).then((result) => {
                    if (result) {
                        window.location.replace("index.php?p=editprofile");
                    }
                });
            </script><?php
                    }
                }
            } ?>

<div class="row mt-5">
    <div class="col-xl-3 col-lg-3">
    </div>
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">แก้ไขข้อมูลสมาชิก</h6>
            </div>
            <div class="card-body">
                <form name="editprofile" id="editprofile" method="POST" action="index.php?p=editprofile">
                    <div class="row align-items-center">
                        <div class="col">
                            <input type="text" class="form-control" name="username" id="username" placeholder="ชื่อผู้ใช้" value="<?= $editprofile["username"]; ?>" readonly>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="text" class="form-control" name="fullname" id="fullname" placeholder="ชื่อ - นามสกุล" value="<?= $editprofile["fullname"]; ?>">
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="text" class="form-control" name="companyname" id="companyname" placeholder="ชื่อบริษัท" value="<?= $editprofile["company"]; ?>">
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="ที่อยู่"><?= $editprofile["address"]; ?></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="email" class="form-control" name="email" id="email" placeholder="อีเมล" value="<?= $editprofile["email"]; ?>">
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="โทรศัพท์" value="<?= $editprofile["phone_number"]; ?>">
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="password" class="form-control" name="password" id="password" placeholder="รหัสผ่าน">
                        </div>
                        <div class="col">
                            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="ยืนยันรหัสผ่าน">
                        </div>
                    </div>
                    <input type="submit" name="edit" id="edit" class="btn btn-primary mt-4" value="บันทึก" />
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3">
    </div>
</div>