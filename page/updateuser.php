<?php
include 'config/connection.php';
if (isset($_POST['approve'])) {
    $updatestatus = "UPDATE member SET userlevel = 'user' WHERE id = {$_POST['iduser']}";
    $userquery = mysqli_query($conn, $updatestatus);
?> <script>
        Swal.fire({
            icon: 'success',
            title: 'อนุมัติสำเร็จ..!',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=manage");
            }
        });
    </script>
<?php
}
if (isset($_POST['noapprove'])) {
    $updatestatus = "UPDATE member SET userlevel = 'ban' WHERE id = {$_POST['iduser']}";
    $userquery = mysqli_query($conn, $updatestatus);
?> <script>
        Swal.fire({
            icon: 'error',
            title: 'ไม่อนุมัติผู้ใช้งานนี้..!',
            showConfirmButton: false,
            timer: 2500
        }).then((result) => {
            if (result) {
                window.location.replace("index.php?p=manage");
            }
        });
    </script>
<?php
} ?>