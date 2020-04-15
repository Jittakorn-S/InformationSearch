<?php
include 'config/connection.php';
if (isset($_POST['forgotpass'])) {
    $email = $_POST['forgotemail'];
    $forgotquery = $conn->query("SELECT * FROM member WHERE email = '$email' AND userlevel != 'admin'");
    $rowforgotquery = mysqli_num_rows($forgotquery);
    if ($rowforgotquery > 0) {
        include 'page\sendmail\forgotmail.php';
    }
}
?>

<div class="row mt-5">
    <div class="col-xl-3 col-lg-3">
    </div>
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">กู้คืนรหัสผ่าน</h6>
            </div>
            <div class="card-body">
                <form name="forgot" id="forgot" method="POST" action="index.php?p=forgotpassword">
                    <div class="row align-items-center">
                        <div class="col">
                            <input type="email" class="form-control" name="forgotemail" id="forgotemail" placeholder="อีเมล" value="">
                        </div>
                    </div>
                    <input type="submit" name="forgotpass" id="forgotpass" class="btn btn-primary mt-3" value="ยืนยัน" />
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3">
    </div>
</div>