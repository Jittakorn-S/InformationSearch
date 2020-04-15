<?php
include 'config/sqlserver.php';
include 'config/connection.php';
if (isset($_POST['appoint'])) {
    $student_id = $_POST['student_id'];
    $company_id = $_SESSION["id"];
}
$sqlappointment = "SELECT * FROM appointment am, member mb WHERE am.company_id = mb.id and am.company_id = '$company_id' and am.student_id = '$student_id'
and am.appoint_status != 'ยกเลิกนัดหมาย'";
$resultappointment = mysqli_query($conn, $sqlappointment);
$row = mysqli_num_rows($resultappointment);
$sqlmember = "SELECT * FROM member WHERE id = {$company_id}";
$resultmember = mysqli_query($conn, $sqlmember);
$companyname = mysqli_fetch_array($resultmember);
$namecompany = $companyname['company'];
if ($row == 0) {
    $addappoint = "INSERT INTO appointment (company_id, student_id, date_create, date_end, report_status, detail, appoint_status)
                   VALUES ('$company_id', '$student_id', NOW(), NOW() + INTERVAL 7 DAY, 'รอดำเนินการ', NULL, 'รอการตอบรับ')";
    $resultquery = mysqli_query($conn, $addappoint);
    $alertsql = "INSERT INTO notifications (name, type, message, status, date)
                 VALUES ('$student_id', 'student', 'มีรายการนัดหมายถึงคุณ', 'unread' ,NOW())";
    $alertquery = mysqli_query($conn, $alertsql);
    if ($resultquery) {
        include 'page\sendmail\firstappointmail.php';
?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'ทำการนัดหมายนักศึกษาสำเร็จ..!',
                text: 'กรุณารอซักครู่ระบบจะพาท่านไปยังหน้าหลัก..',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    window.location.replace("index.php?p=home");
                }
            });
        </script>
    <?php
    }
} else {
    ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ไม่สามารถนัดหมายได้เนื่องจากมีการนัดหมายอยู่แล้ว..!',
            text: 'กรุณารอซักครู่ระบบจะพาท่านไปยังหน้าหลัก',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                window.location.replace("index.php?p=home");
            }
        });
    </script>
<?php
}
