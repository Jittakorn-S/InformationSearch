<?php
$emailforgot = $_GET["email"];
?>

<div class="row mt-5">
    <div class="col-xl-3 col-lg-3">
    </div>
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">เปลี่ยนรหัสผ่าน</h6>
            </div>
            <div class="card-body">
                <form name="changepassword" id="changepassword" method="POST" action="index.php?p=resetpassword">
                    <div class="row align-items-center">
                        <div class="col">
                            <input type="password" class="form-control" name="password" id="password" placeholder="รหัสผ่าน">
                        </div>
                        <div class="col">
                            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="ยืนยันรหัสผ่าน">
                        </div>
                    </div>
                    <input type="hidden" id="emailvalue" name="emailvalue" class="custom-control-input" value="<?php echo $emailforgot; ?>">
                    <input type="submit" name="change" id="change" class="btn btn-primary mt-4" value="เปลี่ยนรหัสผ่าน" />
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3">
    </div>
</div>