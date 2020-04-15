<?php
include '../config/connection.php';
if (isset($_POST['detail'])) {
    $studentid = $_POST['studentid'];
    $qrid = $_POST['qrid'];

    $checkdetail = "SELECT * FROM report where id_faculty = 999 and year = YEAR(CURDATE())";
    $querydetail = mysqli_query($conn, $checkdetail);
    $checkcountfetch = mysqli_fetch_array($querydetail);
    $rowcheckdetail = mysqli_num_rows($querydetail);
    if ($rowcheckdetail > 0) {
        $checkcountdetail = $checkcountfetch["amount"];
        $updatereportdetail = "UPDATE report SET amount = ($checkcountdetail+1) where id_faculty = 999 and year = YEAR(CURDATE())";
        $queryresultdetail = mysqli_query($conn, $updatereportdetail);
    } else {
        $createreportdetail = "INSERT INTO report (amount, id_faculty, year)
        VALUE (1, 999, NOW())";
        $resultcreatedetail = mysqli_query($conn, $createreportdetail);
    }
}
header("Location: http://app.oreg.rmutt.ac.th/RMUTTStudentProfile/MyPortfolio.aspx?login=$studentid&fromurl=QRCODE&codeaccess=$qrid");
