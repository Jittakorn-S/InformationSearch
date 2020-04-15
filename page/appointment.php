<?php
error_reporting(0);
include 'config/sqlserver.php';
include 'config/connection.php';
if ($_SESSION['userlevel'] == "user")
    $sql = "SELECT * FROM appointment WHERE company_id = '{$_SESSION['id']}' and (appoint_status != 'รอนักศึกษาตอบรับ' or appoint_status != 'ตกลงร่วมงาน') and (report_status != '' or report_status = '')";
if ($_SESSION['userlevel'] == "student")
    $sql = "SELECT * FROM appointment ap , member mb  WHERE student_id = '{$_SESSION['id']}' and ap.company_id = mb.id and ap.appoint_status != 'รอนักศึกษาตอบรับ' and ap.appoint_status != 'ยกเลิกนัดหมาย' and (report_status = '' or report_status != '')";
$result = mysqli_query($conn, $sql);
if (isset($_POST["status"])) {
    if ($_SESSION['userlevel'] == "user")
        if ($_POST["status"] != "")
            $sqlstatus = "SELECT * FROM appointment WHERE company_id = '{$_SESSION['id']}' and (appoint_status = '{$_POST['status']}' or report_status = '{$_POST['status']}')";
        else
            $sqlstatus = "SELECT * FROM appointment where company_id = '{$_SESSION['id']}'";

    if ($_SESSION['userlevel'] == "student")
        if ($_POST["status"] != "")
            $sqlstatus = "SELECT * FROM appointment ap , member mb WHERE student_id = '{$_SESSION['id']}' and ap.appoint_status = '{$_POST['status']}' and ap.company_id = mb.id";
        else
            $sqlstatus = "SELECT * FROM appointment ap , member mb  WHERE student_id = '{$_SESSION['id']}' and ap.company_id = mb.id";
    $result = mysqli_query($conn, $sqlstatus);
}
?>
<?php if (isset($_SESSION['username'])) : ?>
    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">แสดงสถานะการนัดหมาย</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form method="POST" action="index.php?p=appointment">
                        <div class="form-group row">
                            <label for="faculty" class="col-md-12">สถานะการนัดหมาย</label>
                            <div class="col-md-12">
                                <select class="form-control" name="status" id="status">
                                    <option <?php if (isset($_POST["status"]) == "") {
                                                echo 'selected';
                                            }  ?> value="">ทั้งหมด</option>';
                                    <option value="ตอบรับ" <?php if ($_POST["status"] == "ตอบรับ") {
                                                                echo 'selected';
                                                            }  ?>>ตอบรับ</option>';
                                    <option value="ไม่ตอบรับ" <?php if ($_POST["status"] == "ไม่ตอบรับ") {
                                                                    echo 'selected';
                                                                }  ?>>ไม่ตอบรับ</option>';
                                    <option value="รอการตอบรับ" <?php if ($_POST["status"] == "รอการตอบรับ") {
                                                                    echo 'selected';
                                                                }  ?>>รอการตอบรับ</option>';
                                    <option value="นัดหมายแล้ว" <?php if ($_POST["status"] == "นัดหมายแล้ว") {
                                                                    echo 'selected';
                                                                }  ?>>นัดหมายแล้ว</option>';
                                    <option value="ยกเลิกนัดหมาย" <?php if ($_POST["status"] == "ยกเลิกนัดหมาย") {
                                                                        echo 'selected';
                                                                    }  ?>>ยกเลิกนัดหมาย</option>';
                                    <option value="ตกลงร่วมงาน" <?php if ($_POST["status"] == "ตกลงร่วมงาน") {
                                                                    echo 'selected';
                                                                }  ?>>ตกลงร่วมงาน</option>';
                                    <option value="ไม่ผ่านการสัมภาษณ์" <?php if ($_POST["status"] == "ไม่ผ่านการสัมภาษณ์") {
                                                                            echo 'selected';
                                                                        }  ?>>ไม่ผ่านการสัมภาษณ์</option>';
                                    <option value="ไม่มาสัมภาษณ์" <?php if ($_POST["status"] == "ไม่มาสัมภาษณ์") {
                                                                        echo 'selected';
                                                                    }  ?>>ไม่มาสัมภาษณ์</option>';

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" ID="search" class="btn btn-primary btn-block" value="ค้นหา" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ผู้ใช้ -->
    <?php if ($_SESSION['userlevel'] == "user") : ?>
        <?php
        $id = $_GET['id'];
        $queryalert = "UPDATE notifications SET status = 'read' WHERE id = $id;";
        $resultalert = mysqli_query($conn, $queryalert);
        ?>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <!-- รายการนัดหมาย -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">รายการนัดหมาย</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>รายชื่อนักศึกษา</th>
                                        <th>คณะ</th>
                                        <th>สาขาวิชา</th>
                                        <th>เกรดเฉลี่ย</th>
                                        <th>วันที่ทำรายการ</th>
                                        <th>สถานะ</th>
                                        <th>ดำเนินการ</th>
                                        <th>รายละเอียด</th>
                                        <th>ติดต่อ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($record = mysqli_fetch_assoc($result)) {
                                        $sqlquery = "SELECT
                                    FACULTYNAME
                                    ,PROGRAMNAME
                                    ,V_FULLNAME
                                    ,GPA
                                    , m.stdid, avatarimage, codeaccess
                                    FROM std_master m, AVSREGDW.dbo.studentinfo s
                                    WHERE s.studentcode = m.stdid
                                    AND m.stdid = '{$record["student_id"]}' ;";
                                        $getresults = $sqlconn->prepare($sqlquery);
                                        $getresults->execute();
                                        $resultsSql = $getresults->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                        <tr>
                                            <!-- รายชื่อนักศึกษา -->
                                            <td><?php echo $resultsSql['V_FULLNAME']; ?></td>
                                            <!-- คณะ -->
                                            <td>
                                                <a href="#" class="badge badge-primary"> <?php echo $resultsSql['FACULTYNAME']; ?></a>
                                            </td>
                                            <!-- สาขาวิชา -->
                                            <td>
                                                <a href="#" class="badge badge-primary"><?php echo $resultsSql['PROGRAMNAME']; ?></a>
                                            </td>
                                            <!-- เกรดเฉลี่ย -->
                                            <td>
                                                <span class="badge badge-pill badge-info"><?php echo $resultsSql['GPA']; ?></span>
                                            </td>
                                            <!-- วันที่ทำรายการ -->
                                            <td>
                                                <a href="#" class="badge badge-primary"><?php echo $record['date_create']; ?></a>
                                            </td>
                                            <!-- สถานะ -->
                                            <td>
                                                <?php if ($record['appoint_status'] == 'ตอบรับ') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-primary"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ไม่ตอบรับ') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'นัดหมายแล้ว') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'รอการตอบรับ') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-warning"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ยกเลิกนัดหมาย') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-secondary"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ไม่ตกลงร่วมงาน') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                <?php endif; ?>

                                            </td>
                                            <!-- ดำเนินการ -->
                                            <td>
                                                <?php if ($record['appoint_status'] == 'ไม่ตอบรับ' || $record['appoint_status'] == 'ยกเลิกนัดหมาย') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success">เสร็จสิ้น</a></div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success">เสร็จสิ้น</a></div>
                                                <?php endif; ?>

                                                <?php if ($record['report_status'] == 'ผ่านการสัมภาษณ์' || $record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success">เสร็จสิ้น</a></div>
                                                <?php endif; ?>

                                                <?php
                                                if ($record['appoint_status'] == 'รอการตอบรับ') : ?>
                                                    <form action="index.php?p=updatestatus" method="POST">
                                                        <input type="hidden" id="idappointment" name="idappointment" class="custom-control-input" value="<?php echo $record['idappointment']; ?>">
                                                        <button type="submit" class="btn btn-danger" name="cancel" id="cancel">ยกเลิก</button>
                                                    </form>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'ตอบรับ') : ?>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#appointbtn">นัดหมาย</button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="appointbtn" tabindex="-1" role="dialog" aria-labelledby="appointmodalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="appointmodalLabel">เลือกวันนัดหมาย</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="index.php?p=createappoint" method="POST">
                                                                    <input type="hidden" id="idappointment" name="idappointment" class="custom-control-input" value="<?php echo $record['idappointment']; ?>">
                                                                    <div class="modal-body">
                                                                        <div class="form-group row">
                                                                            <label for="date_input" class="col-2 col-form-label">วันที่</label>
                                                                            <div class="col-10">
                                                                                <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" name="date_input" id="date_input">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="time_input" class="col-2 col-form-label">เวลา</label>
                                                                            <div class="col-10">
                                                                                <input class="form-control" type="time" value="now" name="time_input" id="time_input">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="note">หมายเหตุ</label>
                                                                            <textarea class="form-control" name="note" id="note" rows="3"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                                                        <button type="submit" class="btn btn-success" name="appoint" id="appoint">ยืนยัน</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                if ($record['appoint_status'] == 'นัดหมายแล้ว') : ?>
                                                    <a href="index.php?p=interviewresults" class="btn btn-info">ผลการสัมภาษณ์</a>
                                                <?php endif; ?>
                                            </td>
                                            <!-- รายละเอียด -->
                                            <td>
                                                <?php if ($record['appoint_status'] == 'รอการตอบรับ' && $record['report_status'] == NULL) : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'ไม่ตอบรับ' || $record['appoint_status'] == 'ยกเลิกนัดหมาย') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php
                                                if ($record['appoint_status'] == 'ตอบรับ' || $record['appoint_status'] == 'นัดหมายแล้ว') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['report_status'] == 'ผ่านการสัมภาษณ์' || $record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <!-- ติดต่อ -->
                                            <td>
                                                <?php if ($record['appoint_status'] == 'ไม่ตอบรับ' || $record['appoint_status'] == 'ยกเลิกนัดหมาย') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'รอการตอบรับ' || $record['appoint_status'] == 'นัดหมายแล้ว') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($record['appoint_status'] == 'ตอบรับ') : ?>
                                                    <form action="index.php?p=chat" method="POST">
                                                        <input type="hidden" id="set_user2" name="set_user1" value="<?= $_SESSION["id"]; ?>"></input>
                                                        <input type="hidden" id="set_user2" name="set_user2" value="<?= $record["student_id"]; ?>"></input>
                                                        <button type="submit" class="btn btn-success"><i class="far fa-comment-dots fa-1x"></i></button>
                                                    </form>
                                                <?php endif; ?>

                                                <?php if ($record['report_status'] == 'ผ่านการสัมภาษณ์' || $record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>
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
    <?php endif; ?>
    <!-- นักศึกษา -->
    <?php if ($_SESSION['userlevel'] == "student") : ?>
        <?php
        $id = $_GET['id'];
        $queryalert = "UPDATE notifications SET status = 'read' WHERE id = $id;";
        $resultalert = mysqli_query($conn, $queryalert);
        ?>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <!-- รายการนัดหมาย -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">รายการนัดหมาย</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div style="overflow-x:auto;">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>บริษัท</th>
                                            <th>ชื่อผู้นัดหมาย</th>
                                            <th>อีเมล์</th>
                                            <th>วันที่ทำรายการ</th>
                                            <th>สถานะ</th>
                                            <th>ดำเนินการ</th>
                                            <th>รายละเอียด</th>
                                            <th>ติดต่อ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($record = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <tr>
                                                <!-- บริษัท -->
                                                <td><?php echo $record['company']; ?></td>
                                                <!-- ชื่อผู้นัดหมาย -->
                                                <td>
                                                    <a href="#" class="badge badge-primary"> <?php echo $record['fullname']; ?></a>
                                                </td>
                                                <!-- อีเมล์ -->
                                                <td>
                                                    <a href="#" class="badge badge-primary"><?php echo $record['email']; ?></a>
                                                </td>
                                                <!-- วันที่ทำรายการ -->
                                                <td>
                                                    <span class="badge badge-pill badge-info"><?php echo $record['date_create']; ?></span>
                                                </td>
                                                <!-- สถานะ -->
                                                <td>
                                                    <?php if ($record['appoint_status'] == 'ตอบรับ') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-primary"><?php echo $record['appoint_status']; ?></a></div>
                                                    <?php elseif ($record['appoint_status'] == 'ไม่ตอบรับ') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['appoint_status']; ?></a></div>
                                                    <?php elseif ($record['appoint_status'] == 'นัดหมายแล้ว') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['appoint_status']; ?></a></div>
                                                    <?php elseif ($record['appoint_status'] == 'รอการตอบรับ') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-warning"><?php echo $record['appoint_status']; ?></a></div>
                                                    <?php elseif ($record['appoint_status'] == 'ยกเลิกนัดหมาย') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-secondary"><?php echo $record['appoint_status']; ?></a></div>
                                                    <?php elseif ($record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['appoint_status']; ?></a></div>
                                                    <?php elseif ($record['appoint_status'] == 'ไม่ตกลงร่วมงาน') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['appoint_status']; ?></a></div>
                                                    <?php elseif ($record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                    <?php elseif ($record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- ดำเนินการ -->
                                                <td>
                                                    <?php if ($record['appoint_status'] == 'ไม่ตอบรับ' || $record['appoint_status'] == 'ยกเลิกนัดหมาย') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-success">เสร็จสิ้น</a></div>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-success">เสร็จสิ้น</a></div>
                                                    <?php endif; ?>

                                                    <?php if ($record['report_status'] == 'ผ่านการสัมภาษณ์' || $record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-success">เสร็จสิ้น</a></div>
                                                    <?php endif; ?>

                                                    <?php
                                                    if ($record['appoint_status'] == 'รอการตอบรับ') : ?>
                                                        <form action="index.php?p=updatestatus" method="post">
                                                            <input type="hidden" id="idappointment" name="idappointment" class="custom-control-input" value="<?php echo $record['idappointment']; ?>">
                                                            <button type="submit" class="btn btn-success" name="accept" id="accept">ตอบรับ</button>
                                                            <button type="submit" class="btn btn-danger" name="noaccept" id="noaccept">ไม่ตอบรับ</button>
                                                        </form>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'ตอบรับ') : ?>
                                                        <div class="text-primary"><a href="#" class="badge badge-secondary">รอการนัดหมาย</a></div>
                                                    <?php endif; ?>
                                                    <?php if ($record['appoint_status'] == 'นัดหมายแล้ว') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- รายละเอียด -->
                                                <td>
                                                    <?php if ($record['appoint_status'] == 'ไม่ตอบรับ' || $record['appoint_status'] == 'ยกเลิกนัดหมาย') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($record['report_status'] == 'ผ่านการสัมภาษณ์' || $record['appoint_status'] == 'ตกลงร่วมงาน') : ?> <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php
                                                    if ($record['appoint_status'] == 'รอการตอบรับ' || $record['appoint_status'] == 'ตอบรับ') : ?>
                                                        <?php $infocompany = "select * from appointment am, member mb where am.idappointment = {$record['idappointment']} and am.company_id = mb.id";
                                                        $resultinfo = mysqli_query($conn, $infocompany);
                                                        $rowinfo = mysqli_fetch_assoc($resultinfo);
                                                        ?>
                                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#infocompany_<?php echo $rowinfo['idappointment']; ?>">ข้อมูลบริษัท</button>
                                                        <div class="modal fade" id="infocompany_<?= $rowinfo['idappointment']; ?>" tabindex="-1" role="dialog" aria-labelledby="infomodalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <form>
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="infomodalLabel">ข้อมูลของบริษัท</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-group row">
                                                                                <label for="companyname" class="col-3 col-form-label">บริษัท</label>
                                                                                <div class="col-9">
                                                                                    <input class="form-control" type="text" value="<?= $rowinfo['company'] ?>" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label for="user" class="col-3 col-form-label">ผู้นัดหมาย</label>
                                                                                <div class="col-9">
                                                                                    <input class="form-control" type="text" value="<?= $rowinfo['fullname'] ?>" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="address">ที่อยู่</label>
                                                                                <textarea class="form-control" id="address" rows="3" readonly><?= $rowinfo['address'] ?></textarea>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label for="user" class="col-3 col-form-label">อีเมล</label>
                                                                                <div class="col-9">
                                                                                    <input class="form-control" type="text" value="<?= $rowinfo['email'] ?>" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label for="user" class="col-3 col-form-label">โทรศัพท์</label>
                                                                                <div class="col-9">
                                                                                    <input class="form-control" type="text" value="<?= $rowinfo['phone_number'] ?>" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-success" data-dismiss="modal">ปิด</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php
                                                    if ($record['appoint_status'] == 'นัดหมายแล้ว') : ?>
                                                        <?php $appointed = "select * from appointment where idappointment = {$record['idappointment']}";
                                                        $resultappointed = mysqli_query($conn, $appointed);
                                                        $queryappointed = mysqli_fetch_assoc($resultappointed);
                                                        $address = "select * from member mb, appointment where idappointment = {$record['idappointment']} and company_id = mb.id";
                                                        $resultaddress = mysqli_query($conn, $address);
                                                        $queryaddress = mysqli_fetch_assoc($resultaddress);
                                                        ?>
                                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#infoappoint_<?php echo $queryaddress['idappointment']; ?>">รายละเอียด</button>
                                                        <div class="modal fade" id="infoappoint_<?= $queryaddress['idappointment']; ?>" tabindex="-1" role="dialog" aria-labelledby="appointmodalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <form>
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="appointmodalLabel">ข้อมูลการนัดหมาย</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-group row">
                                                                                <label for="date_info" class="col-12 col-form-label">วันที่นัดหมาย</label>
                                                                                <div class="col-12">
                                                                                    <input class="form-control" type="text" value="<?= $queryappointed['appoint_date'] ?>" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="note">หมายเหตุ</label>
                                                                                <textarea class="form-control" id="note" rows="3" readonly><?= $queryappointed['detail'] ?></textarea>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="note">ข้อมูลการเดินทาง</label>
                                                                                <textarea class="form-control" id="note" rows="3" readonly><?= $queryaddress['company'], "\n", $queryaddress['address'] ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-success" data-dismiss="modal">ปิด</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- ติดต่อ -->
                                                <td>
                                                    <?php if ($record['appoint_status'] == 'ไม่ตอบรับ' || $record['appoint_status'] == 'ยกเลิกนัดหมาย') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'ตอบรับ') : ?>
                                                        <form action="index.php?p=chat" method="POST">
                                                            <input type="hidden" id="set_user2" name="set_user1" value="<?= $_SESSION["id"]; ?>"></input>
                                                            <input type="hidden" id="set_user2" name="set_user2" value="<?= $record["company_id"]; ?>"></input>
                                                            <button type="submit" class="btn btn-success"><i class="far fa-comment-dots fa-1x"></i></button>
                                                        </form>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($record['appoint_status'] == 'รอการตอบรับ' || $record['appoint_status'] == 'นัดหมายแล้ว') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($record['report_status'] == 'ผ่านการสัมภาษณ์' || $record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                        <div class="d-flex justify-content-center">
                                                            <i class="far fa-times-circle fa-2x"></i>
                                                        </div>
                                                    <?php endif; ?>
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
        </div>
    <?php endif; ?>
<?php else : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ขออภัยกรุณาเข้าสู่ระบบก่อนใช้งาน..!',
            showConfirmButton: false,
            timer: 2000
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=home");
            }
        });
    </script>
<?php endif; ?>
<script>
    $(function() {
        var d = new Date(),
            h = d.getHours(),
            m = d.getMinutes();
        if (h < 10) h = '0' + h;
        if (m < 10) m = '0' + m;
        $('input[type="time"][value="now"]').each(function() {
            $(this).attr({
                'value': h + ':' + m
            });
        });
    });
</script>