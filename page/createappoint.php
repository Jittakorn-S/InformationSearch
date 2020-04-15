<?php
include 'config/sqlserver.php';
include 'config/connection.php';
if (isset($_POST['appoint'])) {
    $appointdate = $_POST['date_input'] . ' ' . $_POST['time_input'];
    $note = $_POST['note'];
    $appoint_update = "UPDATE appointment SET appoint_date = '$appointdate', report_status = 'รอพิจารณา',
    detail = '$note', appoint_status = 'นัดหมายแล้ว' WHERE appointment.idappointment = {$_POST['idappointment']}";
    $update_appoint = mysqli_query($conn, $appoint_update);
    $stdsql = "SELECT * FROM appointment WHERE idappointment = {$_POST['idappointment']}";
    $stdquery = mysqli_query($conn, $stdsql);
    $fetchstd = mysqli_fetch_array($stdquery);
    $resultstd = $fetchstd['student_id'];
    $alertsql = "INSERT INTO notifications (name, type, message, status, date)
                 VALUES ('$resultstd', 'student', 'มีข้อมูลการนัดหมายถึงคุณ', 'unread' ,NOW())";
    $alertquery = mysqli_query($conn, $alertsql);
?> <script>
        Swal.fire({
            icon: 'success',
            title: 'ทำการนัดหมายสำเร็จ..!',
            text: 'กรุณารอซักครู่ระบบจะพาท่านไปยังหน้าจัดการผลการสัมภาษณ์..',
            showConfirmButton: false,
            timer: 3000
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=interviewresults");
            }
        });
    </script> <?php include 'page\sendmail\createappointmail.php';
            } ?>