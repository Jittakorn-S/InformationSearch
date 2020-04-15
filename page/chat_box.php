<?php
include 'config/sqlserver.php';
include 'config/connection.php';
// สำหรับใช้ในตัวอย่างการกำหนด session user_id

if (isset($_SESSION['id'])) {
    $_SESSION['ses_user_id'] = $_SESSION['id'];
}
if (isset($_POST['set_user2'])) {
    $_SESSION['ses_user_id2'] = $_POST['set_user2'];
}
?>
<style type="text/css">
    div#messagesDiv {
        display: block;
        height: 580px;
        overflow: auto;
        background-color: #FDFDE0;
        width: 1700px;
        margin: 5px 0px;
        border: 1px solid #CCC;
    }

    .left_box_chat {
        border: 1px solid #CCC;
        border-radius: 25px;
        margin: 5px;
        padding: 0px 10px;
        display: inline-block;
        float: left;
        clear: both;
        text-align: left;
        background-color: #FFF;
    }

    .right_box_chat {
        border: 1px solid #CCC;
        border-radius: 25px;
        margin: 5px;
        padding: 0px 10px;
        display: inline-block;
        float: right;
        clear: both;
        text-align: right;
        background-color: #9F6;
    }
</style>
<?php if (isset($_SESSION['username'])) : ?>
    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <?php
                    if ($_SESSION['userlevel'] == "user") {
                        $query = "SELECT V_FULLNAME FROM [AVSREGDW].[dbo].[STUDENTINFO] where [STUDENTCODE] = '{$_SESSION['ses_user_id2']}';";
                        $getresults = $sqlconn->prepare($query);
                        $getresults->execute();
                        $row1 = $getresults->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <h6 class="m-0 font-weight-bold text-primary">สนทนากับ : <?= $row1["V_FULLNAME"]; ?></h6>
                    <?php
                    } else   if ($_SESSION['userlevel'] == "student") {
                        $query = "SELECT * FROM member WHERE id = {$_SESSION['ses_user_id2']}";
                        $result = mysqli_query($conn, $query);
                        $row = mysqli_fetch_array($result);
                        echo $_SESSION['ses_user_id1'];
                    ?>
                        <h6 class="m-0 font-weight-bold text-primary">สนทนากับ : <?= $row["fullname"]; ?> จาก <?= $row["company"]; ?></h6>
                    <?php
                    }
                    ?>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="messagesDiv" class="col-xl-12 col-lg-12">
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <input type="hidden" class="form-control" name="userID1" id="userID1" value="<?= (isset($_SESSION['ses_user_id'])) ? $_SESSION['ses_user_id'] : '' ?>" placeholder="UserID 1">
                                <input type="hidden" class="form-control" name="userID2" id="userID2" value="<?= (isset($_SESSION['ses_user_id2'])) ? $_SESSION['ses_user_id2'] : '' ?>" placeholder="UserID 2">
                            </div>
                            <div class="col-xl-12 col-lg-12">
                                <!--  input hidden สำหรับ เก็บ chat_id ล่าสุดที่แสดง-->
                                <input name="h_maxID" type="hidden" id="h_maxID" value="0">
                                <input type="text" class="form-control" name="msg" id="msg" placeholder="Message">
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var load_chat; // กำหนดตัวแปร สำหรับเป็นฟังก์ชั่นเรียกข้อมูลมาแสดง
        var first_load = 1; // กำหนดตัวแปรสำหรับโหลดข้อมูลครั้งแรกให้เท่ากับ 1
        load_chat = function(userID) {
            var maxID = $("#h_maxID").val(); // chat_id ล่าสุดที่แสดง
            $.post("chat/ajax_chat.php", {
                viewData: first_load,
                userID: userID,
                maxID: maxID
            }, function(data) {
                if (first_load == 1) { // ถ้าเป็นการโหลดครั้งแรก ให้ดึงข้อมูลทั้งหมดที่เคยบันทึกมาแสดง
                    for (var k = 0; k < data.length; k++) { // วนลูปแสดงข้อความ chat ที่เคยบันทึกไว้ทั้งหมด
                        if (parseInt(data[0].max_id) > parseInt(maxID)) { // เทียบว่าข้อมูล chat_id .ใหม่กว่าที่แสดงหรือไม่
                            $("#h_maxID").val(data[k].max_id); // เก็บ chat_id เป็น ค่าล่าสุด
                            // แสดงข้อความการ chat มีการประยุกต์ใช้ ตำแหน่งข้อความ เพื่อจัด css class ของข้อความที่แสดง
                            $("#messagesDiv").append("<div class=\"" + data[k].data_align + "_box_chat\">" + data[k].data_msg + "</div>");
                            $("#messagesDiv")[0].scrollTop = $("#messagesDiv")[0].scrollHeight; // เลือน scroll ไปข้อความล่าสุด
                        }
                    };
                } else { // ถ้าเป็นข้อมูลที่เพิ่งส่งไปล่าสุด
                    if (parseInt(data[0].max_id) > parseInt(maxID)) { // เทียบว่าข้อมูล chat_id .ใหม่กว่าที่แสดงหรือไม่
                        $("#h_maxID").val(data[0].max_id); // เก็บ chat_id เป็น ค่าล่าสุด
                        // แสดงข้อความการ chat มีการประยุกต์ใช้ ตำแหน่งข้อความ เพื่อจัด css class ของข้อความที่แสดง
                        $("#messagesDiv").append("<div class=\"" + data[0].data_align + "_box_chat\">" + data[0].data_msg + "</div>");
                        $("#messagesDiv")[0].scrollTop = $("#messagesDiv")[0].scrollHeight; // เลือน scroll ไปข้อความล่าสุด
                    }
                }
                first_load++; // บวกค่า first_load
            });
        }
        // กำหนดให้ทำงานทกๆ 1 วินาทีเพิ่มแสดงข้อมูลคู่สนทนา
        setInterval(function() {
            var userID = $("#userID2").val(); // id user ของผู้รับ
            load_chat(userID); // เรียกใช้งานฟังก์ช่นแสดงข้อความล่าสุด
        }, 1000);

        $(function() {
            /// เมื่อพิมพ์ข้อความ แล้วกดส่ง
            $("#msg").keypress(function(e) { // เมื่อกดที่ ช่องข้อความ
                if (e.keyCode == 13) { // ถ้ากดปุ่ม enter
                    var user1 = $("#userID1").val(); // เก็บ id user  ผู้ใช้ที่ส่ง
                    var user2 = $("#userID2").val(); // เก็บ id user  ผู้ใช้ที่รับ
                    var msg = $("#msg").val(); // เก็บค่าข้อความ
                    $.post("chat/ajax_chat.php", {
                        user1: user1,
                        user2: user2,
                        msg: msg
                    }, function(data) {
                        load_chat(user2); // เรียกใช้งานฟังก์ช่นแสดงข้อความล่าสุด
                        $("#msg").val(""); // ล้างค่าช่องข้อความ ให้พร้อมป้อนข้อความใหม่
                    });
                }
            });
        });
    </script>
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