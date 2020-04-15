<?php
session_start();
session_destroy();
?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'ลงชื่อออกจากระบบสำเร็จ..',
        text: 'กรุณารอซักครู่ระบบจะพาท่านไปยังหน้าหลัก..',
        showConfirmButton: false,
        timer: 2500
    }).then((result) => {
        if (result) {
            window.location.replace("index.php?p=home");
        }
    });
</script>