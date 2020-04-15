<?php
include 'config/connection.php';
include 'config/sqlserver.php';
error_reporting(0);
if (isset($_POST['accept'])) {
    if ($_POST['groupOfDefaultRadios'] == "pass") {
        $report_status = "UPDATE appointment SET report_status = 'ผ่านการสัมภาษณ์', appoint_status = 'รอนักศึกษาตอบรับ' WHERE appointment.idappointment = {$_POST['idappointment']}";
        $stdsql = "SELECT * FROM appointment WHERE idappointment = {$_POST['idappointment']}";
        $stdquery = mysqli_query($conn, $stdsql);
        $fetchstd = mysqli_fetch_array($stdquery);
        $resultstd = $fetchstd['student_id'];
        $alertsql = "INSERT INTO notifications (name, type, message, status, date)
        VALUES ('$resultstd', 'student', 'แจ้งผลผ่านการสัมภาษณ์', 'unread' ,NOW())";
        $alertquery = mysqli_query($conn, $alertsql);
?> <script>
            Swal.fire({
                icon: 'success',
                title: 'แจ้งผลผ่านการสัมภาษณ์..!',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                if (result) {
                    window.location.replace("index.php?p=interviewresults");
                }
            });
        </script> <?php
                    $sqlstatic = "SELECT * FROM staticappointment where come = 'มาตามการนัดหมาย' and year = YEAR(CURDATE())";
                    $querystatic = mysqli_query($conn, $sqlstatic);
                    $checkstatic = mysqli_fetch_array($querystatic);
                    $rowcheckstatic = mysqli_num_rows($querystatic);
                    if ($rowcheckstatic > 0) {
                        $resultcheck = $checkstatic['amount'];
                        $updatestatic = "UPDATE staticappointment SET amount = ($resultcheck+1) where come = 'มาตามการนัดหมาย' and year = YEAR(CURDATE())";
                        $sqlquery = mysqli_query($conn, $updatestatic);
                    } else {
                        $createstatic = "INSERT INTO staticappointment (accept, noaccept, come, nocome, come_yes, come_no, amount, year)
                        VALUE (NULL, NULL, 'มาตามการนัดหมาย', NULL, NULL, NULL, 1, NOW())";
                        $resultquery = mysqli_query($conn, $createstatic);
                    }
                    $updatereport = mysqli_query($conn, $report_status);
                    if ($updatereport) {
                        $sqlstdid = "SELECT student_id FROM appointment WHERE appointment.idappointment = {$_POST['idappointment']}";
                        $resultstdid = mysqli_query($conn, $sqlstdid);
                        $fetchstdid = mysqli_fetch_array($resultstdid);
                        $student_id = $fetchstdid['student_id'];
                        include 'page\sendmail\passinterview.php';
                    ?>
        <?php
                    }
                }
                if ($_POST['groupOfDefaultRadios'] == "nopass") {
                    $report_status = "UPDATE appointment SET report_status = 'ไม่ผ่านการสัมภาษณ์', appoint_status = 'เสร็จสิ้น' WHERE appointment.idappointment = {$_POST['idappointment']}";
                    $stdsql = "SELECT * FROM appointment WHERE idappointment = {$_POST['idappointment']}";
                    $stdquery = mysqli_query($conn, $stdsql);
                    $fetchstd = mysqli_fetch_array($stdquery);
                    $resultstd = $fetchstd['student_id'];
                    $alertsql = "INSERT INTO notifications (name, type, message, status, date)
                    VALUES ('$resultstd', 'student', 'ไม่ผ่านการสัมภาษณ์', 'unread' ,NOW())";
                    $alertquery = mysqli_query($conn, $alertsql);
        ?> <script>
            Swal.fire({
                icon: 'error',
                title: 'แจ้งผลไม่ผ่านการสัมภาษณ์..!',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                if (result) {
                    window.location.replace("index.php?p=interviewresults");
                }
            });
        </script> <?php
                    $sqlstatic = "SELECT * FROM staticappointment where come = 'มาตามการนัดหมาย' and year = YEAR(CURDATE())";
                    $querystatic = mysqli_query($conn, $sqlstatic);
                    $checkstatic = mysqli_fetch_array($querystatic);
                    $rowcheckstatic = mysqli_num_rows($querystatic);
                    if ($rowcheckstatic > 0) {
                        $resultcheck = $checkstatic['amount'];
                        $updatestatic = "UPDATE staticappointment SET amount = ($resultcheck+1) where come = 'มาตามการนัดหมาย' and year = YEAR(CURDATE())";
                        $sqlquery = mysqli_query($conn, $updatestatic);
                    } else {
                        $createstatic = "INSERT INTO staticappointment (accept, noaccept, come, nocome, come_yes, come_no, amount, year)
                        VALUE (NULL, NULL, 'มาตามการนัดหมาย', NULL, NULL, NULL, 1, NOW())";
                        $resultquery = mysqli_query($conn, $createstatic);
                    }
                    $updatereport = mysqli_query($conn, $report_status);
                    if ($updatereport) {
                        $sqlstdid = "SELECT student_id FROM appointment WHERE appointment.idappointment = {$_POST['idappointment']}";
                        $resultstdid = mysqli_query($conn, $sqlstdid);
                        $fetchstdid = mysqli_fetch_array($resultstdid);
                        $student_id = $fetchstdid['student_id'];
                        include 'page\sendmail\nopassinterview.php';
                    }
                }
                if ($_POST['groupOfDefaultRadios'] == "no") {
                    $report_status = "UPDATE appointment SET report_status = 'ไม่มาสัมภาษณ์', appoint_status = 'เสร็จสิ้น' WHERE appointment.idappointment = {$_POST['idappointment']}";
                    $stdsql = "SELECT * FROM appointment WHERE idappointment = {$_POST['idappointment']}";
                    $stdquery = mysqli_query($conn, $stdsql);
                    $fetchstd = mysqli_fetch_array($stdquery);
                    $resultstd = $fetchstd['student_id'];
                    $alertsql = "INSERT INTO notifications (name, type, message, status, date)
                    VALUES ('$resultstd', 'student', 'ไม่มาสัมภาษณ์', 'unread' ,NOW())";
                    $alertquery = mysqli_query($conn, $alertsql);
                    ?><script>
            Swal.fire({
                icon: 'error',
                title: 'แจ้งผลไม่มาสัมภาษณ์..!',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                if (result) {
                    window.location.replace("index.php?p=interviewresults");
                }
            });
        </script> <?php
                    $sqlstatic = "SELECT * FROM staticappointment where nocome = 'ไม่มาตามการนัดหมาย' and year = YEAR(CURDATE())";
                    $querystatic = mysqli_query($conn, $sqlstatic);
                    $checkstatic = mysqli_fetch_array($querystatic);
                    $rowcheckstatic = mysqli_num_rows($querystatic);
                    if ($rowcheckstatic > 0) {
                        $resultcheck = $checkstatic['amount'];
                        $updatestatic = "UPDATE staticappointment SET amount = ($resultcheck+1) where nocome = 'ไม่มาตามการนัดหมาย' and year = YEAR(CURDATE())";
                        $sqlquery = mysqli_query($conn, $updatestatic);
                    } else {
                        $createstatic = "INSERT INTO staticappointment (accept, noaccept, come, nocome, come_yes, come_no, amount, year)
                        VALUE (NULL, NULL, NULL, 'ไม่มาตามการนัดหมาย', NULL, NULL, 1, NOW())";
                        $resultquery = mysqli_query($conn, $createstatic);
                    }
                }
                $updatereport = mysqli_query($conn, $report_status);
                if ($updatereport) {
                    $sqlstdid = "SELECT student_id FROM appointment WHERE appointment.idappointment = {$_POST['idappointment']}";
                    $resultstdid = mysqli_query($conn, $sqlstdid);
                    $fetchstdid = mysqli_fetch_array($resultstdid);
                    $student_id = $fetchstdid['student_id'];
                    include 'page\sendmail\nocomeinterview.php';
                }
            }
            //---------------------------------------------------------------------
            //นักศึกษาตอบรับ
            if (isset($_POST['studentagree'])) {
                if ($_POST['groupOfDefaultRadiosstudent'] == "agree") {
                    $report_agree = "UPDATE appointment SET appoint_status = 'ตกลงร่วมงาน' WHERE appointment.idappointment = {$_POST['idappointment']}";
                    $usersql = "SELECT * FROM appointment WHERE idappointment = {$_POST['idappointment']}";
                    $userquery = mysqli_query($conn, $usersql);
                    $fetchuser = mysqli_fetch_array($userquery);
                    $resultuser = $fetchuser['company_id'];
                    $alertsql = "INSERT INTO notifications (name, type, message, status, date)
                                 VALUES ('$resultuser', 'user', 'ตกลงร่วมงาน', 'unread' ,NOW())";
                    $alertquery = mysqli_query($conn, $alertsql);
                    ?> <script>
            Swal.fire({
                icon: 'success',
                title: 'ตกลงร่วมงานสำเร็จ..!',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                if (result) {
                    window.location.replace("index.php?p=interviewresults");
                }
            });
        </script> <?php
                    $sqlstatic = "SELECT * FROM staticappointment where come_yes = 'มาตามการนัดหมายและตกลงร่วมงาน' and year = YEAR(CURDATE())";
                    $querystatic = mysqli_query($conn, $sqlstatic);
                    $checkstatic = mysqli_fetch_array($querystatic);
                    $rowcheckstatic = mysqli_num_rows($querystatic);
                    if ($rowcheckstatic > 0) {
                        $resultcheck = $checkstatic['amount'];
                        $updatestatic = "UPDATE staticappointment SET amount = ($resultcheck+1) where come_yes = 'มาตามการนัดหมายและตกลงร่วมงาน' and year = YEAR(CURDATE())";
                        $sqlquery = mysqli_query($conn, $updatestatic);
                    } else {
                        $createstatic = "INSERT INTO staticappointment (accept, noaccept, come, nocome, come_yes, come_no, amount, year)
                        VALUE (NULL, NULL, NULL, NULL, 'มาตามการนัดหมายและตกลงร่วมงาน', NULL, 1, NOW())";
                        $resultquery = mysqli_query($conn, $createstatic);
                    }
                    $updateagree = mysqli_query($conn, $report_agree);
                    if ($updateagree) {
                        include 'page\sendmail\joinjob.php';
                    }
                }
                if ($_POST['groupOfDefaultRadiosstudent'] == "noagree") {
                    $report_noagree = "UPDATE appointment SET appoint_status = 'ไม่ตกลงร่วมงาน' WHERE appointment.idappointment = {$_POST['idappointment']}";
                    $usersql = "SELECT * FROM appointment WHERE idappointment = {$_POST['idappointment']}";
                    $userquery = mysqli_query($conn, $usersql);
                    $fetchuser = mysqli_fetch_array($userquery);
                    $resultuser = $fetchuser['company_id'];
                    $alertsql = "INSERT INTO notifications (name, type, message, status, date)
                                 VALUES ('$resultuser', 'user', 'ไม่ตกลงร่วมงาน', 'unread' ,NOW())";
                    $alertquery = mysqli_query($conn, $alertsql);
                    ?> <script>
            Swal.fire({
                icon: 'error',
                title: 'ไม่ตกลงร่วมงานสำเร็จ..!',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                if (result) {
                    window.location.replace("index.php?p=interviewresults");
                }
            });
        </script> <?php
                    $sqlstatic = "SELECT * FROM staticappointment where come_no = 'มาตามการนัดหมายแต่ไม่ตกลงร่วมงาน' and year = YEAR(CURDATE())";
                    $querystatic = mysqli_query($conn, $sqlstatic);
                    $checkstatic = mysqli_fetch_array($querystatic);
                    $rowcheckstatic = mysqli_num_rows($querystatic);
                    if ($rowcheckstatic > 0) {
                        $resultcheck = $checkstatic['amount'];
                        $updatestatic = "UPDATE staticappointment SET amount = ($resultcheck+1) where come_no = 'มาตามการนัดหมายแต่ไม่ตกลงร่วมงาน' and year = YEAR(CURDATE())";
                        $sqlquery = mysqli_query($conn, $updatestatic);
                    } else {
                        $createstatic = "INSERT INTO staticappointment (accept, noaccept, come, nocome, come_yes, come_no, amount, year)
            VALUE (NULL, NULL, NULL, NULL, NULL, 'มาตามการนัดหมายแต่ไม่ตกลงร่วมงาน', 1, NOW())";
                        $resultquery = mysqli_query($conn, $createstatic);
                    }
                    $updatenoagree = mysqli_query($conn, $report_noagree);
                    if ($updatenoagree) {
                        include 'page\sendmail\nojoinjob.php';
                    }
                }
            }
