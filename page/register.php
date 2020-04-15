<div class="row mt-5">
    <div class="col-xl-3 col-lg-3">
    </div>
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">ลงทะเบียนสมาชิก</h6>
            </div>
            <div class="card-body">
                <form name="signup" id="signup" method="POST" action="index.php?p=register_action" autocomplete="off">
                    <div class="row align-items-center">
                        <div class="col">
                            <input type="text" class="form-control" name="username" id="username" pattern="[a-zA-Z0-9]+" minlength="4" maxlength="15" placeholder="ชื่อผู้ใช้" title="กรุณากรอกตัวอักษรภาษาอังกฤษหรือตัวเลขเท่านั้น" required>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="text" class="form-control" name="fullname" id="fullname" placeholder="ชื่อ - นามสกุล" required>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="text" class="form-control" name="companyname" id="companyname" placeholder="ชื่อบริษัท" required>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="ที่อยู่" required></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="email" class="form-control" name="email" id="email" placeholder="อีเมล" required>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="โทรศัพท์" pattern="[0-9]+" title="กรอกตัวเลขเท่านั้น" required>
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col">
                            <input type="password" class="form-control" name="password" id="password" placeholder="รหัสผ่าน" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="รหัสผ่านต้องประกอบไปด้วยตัวเลขอย่างน้อย 1 ตัว และตัวพิมพ์ใหญ่ 1 ตัว ทั้งหมด 8 ตัวอักษร" required>
                        </div>
                        <div class="col">
                            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="ยืนยันรหัสผ่าน" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="รหัสผ่านต้องประกอบไปด้วยตัวเลขอย่างน้อย 1 ตัว และตัวพิมพ์ใหญ่ 1 ตัว ทั้งหมด 8 ตัวอักษร" required>
                        </div>
                    </div>
                    <input type="submit" name="submit" class="btn btn-primary mt-4" value="ลงทะบียน" />
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3">
    </div>
</div>