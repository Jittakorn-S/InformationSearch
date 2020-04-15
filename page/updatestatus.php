<?php
include 'config/sqlserver.php';
include 'config/connection.php';
if (isset($_POST['accept'])) {
    $updatestatus = "UPDATE appointment SET appoint_status = 'ตอบรับ' WHERE appointment.idappointment = {$_POST['idappointment']}";
    $usersql = "SELECT * FROM appointment WHERE idappointment = {$_POST['idappointment']}";
    $userquery = mysqli_query($conn, $usersql);
    $fetchuser = mysqli_fetch_array($userquery);
    $resultuser = $fetchuser['company_id'];
    $alertsql = "INSERT INTO notifications (name, type, message, status, date)
                 VALUES ('$resultuser', 'user', 'นักศึกษาตอบรับการนัดหมายของคุณ', 'unread' ,NOW())";
    $alertquery = mysqli_query($conn, $alertsql);
?> <script>
        Swal.fire({
            icon: 'success',
            title: 'ตอบรับนัดหมายสำเร็จ..!',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=appointment");
            }
        });
    </script> <?php include 'page\sendmail\acceptappointmail.php'; ?>
    <?php
    $sqlstatic = "SELECT * FROM staticappointment where accept = 'ตอบรับ' and year = YEAR(CURDATE())";
    $querystatic = mysqli_query($conn, $sqlstatic);
    $checkstatic = mysqli_fetch_array($querystatic);
    $rowcheckstatic = mysqli_num_rows($querystatic);
    if ($rowcheckstatic > 0) {
        $resultcheck = $checkstatic['amount'];
        $updatestatic = "UPDATE staticappointment SET amount = ($resultcheck+1) where accept = 'ตอบรับ' and year = YEAR(CURDATE())";
        $sqlquery = mysqli_query($conn, $updatestatic);
    } else {
        $createstatic = "INSERT INTO staticappointment (accept, noaccept, come, nocome, come_yes, come_no, amount, year)
                    VALUE ('ตอบรับ', NULL, NULL, NULL, NULL, NULL, 1, NOW())";
        $resultquery = mysqli_query($conn, $createstatic);
    }
}
if (isset($_POST['noaccept'])) {
    $updatestatus = "UPDATE appointment SET appoint_status = 'ไม่ตอบรับ' WHERE appointment.idappointment = {$_POST['idappointment']}";
    $usersql = "SELECT * FROM appointment WHERE idappointment = {$_POST['idappointment']}";
    $userquery = mysqli_query($conn, $usersql);
    $fetchuser = mysqli_fetch_array($userquery);
    $resultuser = $fetchuser['company_id'];
    $alertsql = "INSERT INTO notifications (name, type, message, status, date)
                 VALUES ('$resultuser', 'user', 'ไม่ตอบรับ', 'unread' ,NOW())";
    $alertquery = mysqli_query($conn, $alertsql);
    ?> <script>
        Swal.fire({
            icon: 'error',
            title: 'ไม่ตอบรับการนัดหมาย..!',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=appointment");
            }
        });
    </script> <?php include 'page\sendmail\noacceptappointmail.php'; ?>
    <?php
    $sqlstatic = "SELECT * FROM staticappointment where noaccept = 'ไม่ตอบรับ' and year = YEAR(CURDATE())";
    $querystatic = mysqli_query($conn, $sqlstatic);
    $checkstatic = mysqli_fetch_array($querystatic);
    $rowcheckstatic = mysqli_num_rows($querystatic);
    if ($rowcheckstatic > 0) {
        $resultcheck = $checkstatic['amount'];
        $updatestatic = "UPDATE staticappointment SET amount = ($resultcheck+1) where noaccept = 'ไม่ตอบรับ' and year = YEAR(CURDATE())";
        $sqlquery = mysqli_query($conn, $updatestatic);
    } else {
        $createstatic = "INSERT INTO staticappointment (accept, noaccept, come, nocome, come_yes, come_no, amount, year)
                VALUE (NULL, 'ไม่ตอบรับ', NULL, NULL, NULL, NULL, 1, NOW())";
        $resultquery = mysqli_query($conn, $createstatic);
    }
}
if (isset($_POST['cancel'])) {
    $updatestatus = "UPDATE appointment SET appoint_status = 'ยกเลิกนัดหมาย' WHERE appointment.idappointment = {$_POST['idappointment']}";
    ?> <script>
        Swal.fire({
            icon: 'error',
            title: 'ยกเลิกการนัดหมาย..!',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=appointment");
            }
        });
    </script>
<?php
}
$updatequery = mysqli_query($conn, $updatestatus);
?>