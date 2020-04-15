<?php
include 'config/connection.php';
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if ($_GET['type'] == "member") {
    if (isset($_POST['username'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $encrypt = md5($password);
        $query = "SELECT * FROM member WHERE username = '$username' AND password = '$encrypt' and userlevel != 'admin'";
        $result = mysqli_query($conn, $query);
        $checklevel = mysqli_fetch_array($result);
        if ($checklevel['userlevel'] == 'user' && mysqli_num_rows($result) == 1) {
            $_SESSION['id'] = $checklevel['id'];
            $_SESSION['username'] = $checklevel['username'];
            $_SESSION['fullname'] = $checklevel['fullname'];
            $_SESSION['userlevel'] = "user";
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'เข้าสู่ระบบสำเร็จ..!',
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
        } else if ($checklevel['userlevel'] == 'pending' && mysqli_num_rows($result) == 1) {
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'เข้าสู่ระบบล้มเหลว..!',
                    text: 'กรุณารอสักครู่ ผู้ดูแลระบบกำลังตรวจสอบและอนุมัติผู้ใช้งานนี้',
                    showConfirmButton: false,
                    timer: 3000
                }).then((result) => {
                    if (result) {
                        window.location.replace("index.php?p=home");
                    }
                });
            </script>
        <?php
        } else if ($checklevel['userlevel'] == 'ban' && mysqli_num_rows($result) == 1) {
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'เข้าสู่ระบบล้มเหลว..!',
                    text: 'กรุณาติดต่อผู้ดูแลระบบ เนื่องจากบัญชีของคุณไม่ถูกอนุมัติ',
                    showConfirmButton: false,
                    timer: 3000
                }).then((result) => {
                    if (result) {
                        window.location.replace("index.php?p=home");
                    }
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'เข้าสู่ระบบล้มเหลว..!',
                    text: 'ชื่อผู้ใช้งาน หรือ รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่',
                    showConfirmButton: false,
                    timer: 2500
                }).then((result) => {
                    if (result) {
                        window.location.replace("index.php?p=home");
                    }
                });
            </script>
        <?php
        }
    }
} else if ($_GET['type'] == "student") {
    if (isset($_POST['username'])) {
        include 'config/sqlserver.php';
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT STUDENTCODE, V_FULLNAME
        FROM [AVSREGDW].[dbo].[STUDENTINFO]
        where STUDENTCODE = '{$username}' and PASSWORD = '{$password}';";
        $getresults = $sqlconn->prepare($query);
        $getresults->execute();
        $row1 = $getresults->fetch(PDO::FETCH_ASSOC);
        if ($row1 == true) {
            $_SESSION['id'] = $row1['STUDENTCODE'];
            $_SESSION['username'] =  $_SESSION['id'];
            $_SESSION['fullname'] = $row1['V_FULLNAME'];
            $_SESSION['userlevel'] = "student";
        ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'เข้าสู่ระบบสำเร็จ..!',
                    text: 'กรุณารอซักครู่ระบบจะพาท่านไปยังหน้าหลัก..',
                    showConfirmButton: false,
                    timer: 2500
                }).then((result) => {
                    if (result) {
                        window.location.replace("index.php?p=home");
                    }
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'เข้าสู่ระบบล้มเหลว..!',
                    text: 'ชื่อผู้ใช้งาน หรือ รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่',
                    showConfirmButton: false,
                    timer: 2500
                }).then((result) => {
                    if (result) {
                        window.location.replace("index.php?p=home");
                    }
                });
            </script>
        <?php
        }
    } else {
        header("Location: index.php?p=home");
    }
} else if ($_GET['type'] == "admin") {
    if (isset($_POST['username'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $encrypt = md5($password);
        $query = "SELECT * FROM member WHERE username = '$username' AND password = '$encrypt' and userlevel ='admin'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['userlevel'] = $row['userlevel'];
        ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'เข้าสู่ระบบสำเร็จ..!',
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
        } else {
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'เข้าสู่ระบบล้มเหลว..!',
                    text: 'ชื่อผู้ใช้งาน หรือ รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่',
                    showConfirmButton: false,
                    timer: 2500
                }).then((result) => {
                    if (result) {
                        window.location.replace("index.php?p=home");
                    }
                });
            </script>
<?php
        }
    } else {
        header("Location: index.php?p=home");
    }
}
