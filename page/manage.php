<?php
error_reporting(0);
include 'config/connection.php';
if ($_SESSION['userlevel'] == "admin")
    $sql = "SELECT * FROM member WHERE userlevel = 'pending' OR userlevel = 'ban'";
$result = mysqli_query($conn, $sql);
?>
<?php if ($_SESSION['userlevel'] == "admin") : ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">จัดการรายการผู้ใช้</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>รายชื่อผู้ใช้</th>
                                    <th>ชื่อสมาชิก</th>
                                    <th>ชื่อสถานประกอบ</th>
                                    <th>อีเมล</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>สถานะ</th>
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($record = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <!-- รายชื่อผู้ใช้ -->
                                        <td><?php echo $record['username']; ?></td>
                                        <!-- ชื่อสมาชิก -->
                                        <td>
                                            <a href="#" class="badge badge-primary"> <?php echo $record['fullname']; ?></a>
                                        </td>
                                        <!-- ชื่อสถานประกอบ -->
                                        <td>
                                            <a href="#" class="badge badge-success"><?php echo $record['company']; ?></a>
                                        </td>
                                        <!-- อีเมล -->
                                        <td>
                                            <span class="badge badge-pill badge-info"><?php echo $record['email']; ?></span>
                                        </td>
                                        <!-- เบอร์โทรศัพท์ -->
                                        <td>
                                            <a href="#" class="badge badge-primary"><?php echo $record['phone_number']; ?></a>
                                        </td>
                                        <!-- สถานะ -->
                                        <td>
                                            <a href="#" class="badge badge-default"><?php echo $record['userlevel']; ?></a>
                                        </td>
                                        <!-- ดำเนินการ -->
                                        <td>
                                            <form action="index.php?p=updateuser" method="post">
                                                <input type="hidden" id="iduser" name="iduser" class="custom-control-input" value="<?php echo $record['id']; ?>">
                                                <button type="submit" class="btn btn-success" name="approve" id="approve">อนุมัติ</button>
                                                <button type="submit" class="btn btn-danger" name="noapprove" id="noapprove">ไม่อนุมัติ</button>
                                            </form>
                                        </td>
                                    </tr>
                            </tbody>
                        <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ขออภัยกรุณาเข้าสู่ระบบก่อนใช้งาน..!',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=home");
            }
        });
    </script>
<?php endif; ?>