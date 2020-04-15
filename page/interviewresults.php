<?php
error_reporting(0);
include 'config/sqlserver.php';
include 'config/connection.php';
if ($_SESSION['userlevel'] == "user")
    $sql = "SELECT * FROM appointment WHERE company_id = '{$_SESSION["id"]}' and report_status != '' and appoint_status != ''";
if ($_SESSION['userlevel'] == "student")
    $sql = "SELECT * FROM appointment ap , member mb  WHERE student_id = '{$_SESSION["id"]}' and ap.company_id = mb.id and idappointment";
$result = mysqli_query($conn, $sql);
if (isset($_POST["status"])) {
    if ($_SESSION['userlevel'] == "user")
        if ($_POST["status"] != "")
            $sqlstatus = "SELECT * FROM appointment WHERE company_id = '{$_SESSION['id']}' and report_status = '{$_POST['status']}'";
        else
            $sqlstatus = "SELECT * FROM appointment where company_id = '{$_SESSION['id']}' and report_status != ''";

    if ($_SESSION['userlevel'] == "student")
        if ($_POST["status"] != "")
            $sqlstatus = "SELECT * FROM appointment ap , member mb WHERE student_id = '{$_SESSION['id']}' and ap.report_status = '{$_POST['status']}' and ap.company_id = mb.id";
        else
            $sqlstatus = "SELECT * FROM appointment ap , member mb  WHERE student_id = '{$_SESSION['id']}' and ap.report_status != '' and ap.company_id = mb.id";
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
                    <h6 class="m-0 font-weight-bold text-primary">แสดงสถานะผลสัมภาษณ์</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form method="POST" action="index.php?p=interviewresults">
                        <div class="form-group row">
                            <label for="faculty" class="col-md-12">สถานะผลสัมภาษณ์</label>
                            <div class="col-md-12">
                                <select class="form-control" name="status" id="status">
                                    <option <?php if ($_POST["status"] == "") {
                                                echo 'selected';
                                            }  ?> value="">ทั้งหมด</option>';
                                    <option value="ผ่านการสัมภาษณ์" <?php if ($_POST["status"] == "ผ่านการสัมภาษณ์") {
                                                                        echo 'selected';
                                                                    }  ?>>ผ่านการสัมภาษณ์</option>';
                                    <option value="ไม่ผ่านการสัมภาษณ์" <?php if ($_POST["status"] == "ไม่ผ่านการสัมภาษณ์") {
                                                                            echo 'selected';
                                                                        }  ?>>ไม่ผ่านการสัมภาษณ์</option>';
                                    <option value="รอพิจารณา" <?php if ($_POST["status"] == "รอพิจารณา") {
                                                                    echo 'selected';
                                                                }  ?>>รอพิจารณา</option>';
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
    <!-- Content Row -->
    <?php if ($_SESSION['userlevel'] == "user") :
        $id = $_GET['id'];
        $queryalert = "UPDATE notifications SET status = 'read' WHERE id = $id;";
        $resultalert = mysqli_query($conn, $queryalert);
    ?>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">ผลการสัมภาษณ์</h6>
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
                                        <th>ผลการสัมภาษณ์</th>
                                        <th>ดำเนินการ</th>
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
                                                <?php elseif ($record['appoint_status'] == 'รอนักศึกษาตอบรับ') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-warning"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ไม่ตกลงร่วมงาน') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'เสร็จสิ้น') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php endif; ?>

                                            </td>
                                            <!-- ผลการสัมภาษณ์ -->
                                            <td>
                                                <?php if ($record['appoint_status'] == 'นัดหมายแล้ว' && $record['report_status'] == 'รอพิจารณา') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-warning"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'นัดหมายแล้ว' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'นัดหมายแล้ว' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'นัดหมายแล้ว' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'รอนักศึกษาตอบรับ' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ตกลงร่วมงาน' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ไม่ตกลงร่วมงาน' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                <?php endif; ?>
                                            </td>
                                            <!-- ดำเนินการ -->
                                            <td>
                                                <?php if ($record['appoint_status'] == 'นัดหมายแล้ว' && $record['report_status'] == 'รอพิจารณา') : ?>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#interviewresults">แจ้งผล</button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="interviewresults" tabindex="-1" role="dialog" aria-labelledby="appointmodalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="appointmodalLabel">ผลการสัมภาษณ์</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="index.php?p=updateinterview" method="post">
                                                                    <div class="modal-body">
                                                                        <!-- Group of default radios - option 1 -->
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="groupOfDefaultRadios" value="pass">
                                                                            <input type="hidden" id="idappointment" name="idappointment" class="custom-control-input" value="<?php echo $record['idappointment']; ?>">
                                                                            <label class="custom-control-label" for="defaultGroupExample1">ผ่านการสัมภาษณ์</label>
                                                                        </div>
                                                                        <!-- Group of default radios - option 2 -->
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="groupOfDefaultRadios" value="nopass">
                                                                            <input type="hidden" id="idappointment" name="idappointment" class="custom-control-input" value="<?php echo $record['idappointment']; ?>">
                                                                            <label class="custom-control-label" for="defaultGroupExample2">ไม่ผ่านการสัมภาษณ์</label>
                                                                        </div>
                                                                        <!-- Group of default radios - option 3 -->
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" class="custom-control-input" id="defaultGroupExample3" name="groupOfDefaultRadios" value="no">
                                                                            <input type="hidden" id="idappointment" name="idappointment" class="custom-control-input" value="<?php echo $record['idappointment']; ?>">
                                                                            <label class="custom-control-label" for="defaultGroupExample3">ไม่มาสัมภาษณ์</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                                                        <button type="submit" class="btn btn-success" name="accept" id="accept">ยืนยัน</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'รอนักศึกษาตอบรับ' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'ตกลงร่วมงาน' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'ไม่ตกลงร่วมงาน' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
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
    <?php if ($_SESSION['userlevel'] == "student") :
        $id = $_GET['id'];
        $queryalert = "UPDATE notifications SET status = 'read' WHERE id = $id;";
        $resultalert = mysqli_query($conn, $queryalert);
    ?>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <!-- รายการผลการสัมภาษณ์ -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">ผลการสัมภาษณ์</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>บริษัท</th>
                                        <th>ชื่อผู้นัดหมาย</th>
                                        <th>อีเมล</th>
                                        <th>วันที่ทำรายการ</th>
                                        <th>สถานะ</th>
                                        <th>ผลการสัมภาษณ์</th>
                                        <th>รายละเอียด</th>
                                        <th>ดำเนินการ</th>
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
                                            <!-- อีเมล -->
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
                                                <?php elseif ($record['appoint_status'] == 'รอนักศึกษาตอบรับ') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-secondary"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ตกลงร่วมงาน') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ไม่ตกลงร่วมงาน') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'เสร็จสิ้น') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['appoint_status']; ?></a></div>
                                                <?php endif; ?>
                                            </td>
                                            <!-- ผลการสัมภาษณ์ -->
                                            <td>
                                                <?php if ($record['appoint_status'] == 'รอการตอบรับ' && $record['report_status'] ==  NULL) : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php elseif ($record['appoint_status'] == 'ตอบรับ' && $record['report_status'] ==  NULL) : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php elseif ($record['appoint_status'] == 'ไม่ตอบรับ' && $record['report_status'] ==  NULL) : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php elseif ($record['appoint_status'] == 'นัดหมายแล้ว' && $record['report_status'] == 'รอพิจารณา') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-warning"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'รอนักศึกษาตอบรับ' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ตกลงร่วมงาน' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'ไม่ตกลงร่วมงาน' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-success"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                <?php elseif ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="text-primary"><a href="#" class="badge badge-danger"><?php echo $record['report_status']; ?></a></div>
                                                <?php endif; ?>
                                            </td>
                                            <!-- รายละเอียด -->
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <i class="far fa-times-circle fa-2x"></i>
                                                </div>
                                            </td>
                                            <!-- ดำเนินการ -->
                                            <td>
                                                <?php if ($record['appoint_status'] == 'รอการตอบรับ' && $record['report_status'] ==  NULL) : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php elseif ($record['appoint_status'] == 'นัดหมายแล้ว' && $record['report_status'] == 'รอพิจารณา') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-clock fa-2x"></i>
                                                    </div>
                                                <?php elseif ($record['appoint_status'] == 'ตอบรับ' && $record['report_status'] ==  NULL) : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>
                                                <?php elseif ($record['appoint_status'] == 'ไม่ตอบรับ' && $record['report_status'] ==  NULL) : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <i class="far fa-times-circle fa-2x"></i>
                                                    </div>

                                                <?php elseif ($record['appoint_status'] == 'รอนักศึกษาตอบรับ' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#interview_results">แจ้งผล</button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="interview_results" tabindex="-1" role="dialog" aria-labelledby="studentacceptmodalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="studentacceptmodalLabel">ตกลงร่วมงานหรือไม่ ?</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="index.php?p=updateinterview" method="post">
                                                                    <div class="modal-body">
                                                                        <!-- Group of default radios - option 1 -->
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" class="custom-control-input" id="studentagree1" name="groupOfDefaultRadiosstudent" value="agree">
                                                                            <input type="hidden" id="idappointment" name="idappointment" class="custom-control-input" value="<?php echo $record['idappointment']; ?>">
                                                                            <label class="custom-control-label" for="studentagree1">ตกลง</label>
                                                                        </div>
                                                                        <!-- Group of default radios - option 2 -->
                                                                        <div class="custom-control custom-radio">
                                                                            <input type="radio" class="custom-control-input" id="studentagree2" name="groupOfDefaultRadiosstudent" value="noagree">
                                                                            <input type="hidden" id="idappointment" name="idappointment" class="custom-control-input" value="<?php echo $record['idappointment']; ?>">
                                                                            <label class="custom-control-label" for="studentagree2">ไม่ตกลง</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                                                        <button type="submit" class="btn btn-success" name="studentagree" id="studentagree">ยืนยัน</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php elseif ($record['appoint_status'] == 'ตกลงร่วมงาน' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
                                                    </div>

                                                <?php elseif ($record['appoint_status'] == 'ไม่ตกลงร่วมงาน' && $record['report_status'] == 'ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
                                                    </div>

                                                <?php elseif ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่ผ่านการสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
                                                    </div>

                                                <?php elseif ($record['appoint_status'] == 'เสร็จสิ้น' && $record['report_status'] == 'ไม่มาสัมภาษณ์') : ?>
                                                    <div class="d-flex justify-content-center">
                                                        <span style="color:#5cb85c;">
                                                            <i class="fas fa-check fa-2x"></i>
                                                        </span>
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