<?php
include 'config/sqlserver.php';
include 'config/connection.php';
session_start();
$faculty = mysqli_real_escape_string($conn, $_POST['faculty']);
$department = mysqli_real_escape_string($conn, $_POST['department']);
$gpa = mysqli_real_escape_string($conn, $_POST['gpa']);
if ($faculty != "" && $department == "") {
    $sqlquery = "SELECT '===STUDENTINFO===',
    FACULTYNAME
    ,PROGRAMNAME
    ,V_FULLNAME
    ,GPA,
    '===std_master===', m.stdid, avatarimage, codeaccess
    FROM std_master m, AVSREGDW.dbo.studentinfo s
    WHERE s.studentcode = m.stdid AND s.FACULTYID = '$faculty' AND s.GPA LIKE '$gpa%' ORDER BY s.GPA DESC";
    $checkyear = "SELECT * FROM report where id_faculty = '$faculty'
                and id_department = '' and year = YEAR(CURDATE())";
    $querycheck = mysqli_query($conn, $checkyear);
    $checkfetch = mysqli_fetch_array($querycheck);
    $rowcheck = mysqli_num_rows($querycheck);
    if ($rowcheck > 0) {
        $checkstudent = $checkfetch["amount"];
        $updatereportsearh = "UPDATE report SET amount = ($checkstudent+1) where id_faculty = '$faculty'
    and id_department = '' and year = YEAR(CURDATE())";
        $queryresultreport = mysqli_query($conn, $updatereportsearh);
    } else {
        $createreportsearh = "INSERT INTO report (amount, id_faculty, id_department, year)
                    VALUE (1, '$faculty', '', NOW())";
        $resultcreate = mysqli_query($conn, $createreportsearh);
    }
} else if ($faculty != "" && $department != "") {
    $sqlquery = "SELECT '===STUDENTINFO===',
    FACULTYNAME
    ,PROGRAMNAME
    ,V_FULLNAME
    ,GPA,
    '===std_master===', m.stdid, avatarimage, codeaccess
    FROM std_master m, AVSREGDW.dbo.studentinfo s
    WHERE s.studentcode = m.stdid AND s.FACULTYID = '$faculty'
    AND s.PROGRAMNAME LIKE N'%$department%' AND s.GPA LIKE '$gpa%' ORDER BY s.GPA DESC";
    $checkyear = "SELECT * FROM report where id_faculty = '$faculty'
                and id_department = '$department' and year = YEAR(CURDATE())";
    $querycheck = mysqli_query($conn, $checkyear);
    $checkfetch = mysqli_fetch_array($querycheck);
    $rowcheck = mysqli_num_rows($querycheck);
    if ($rowcheck > 0) {
        $checkstudent = $checkfetch["amount"];
        $updatereportsearh = "UPDATE report SET amount = ($checkstudent+1) where id_faculty = '$faculty'
    and id_department = '$department' and year = YEAR(CURDATE())";
        $queryresultreport = mysqli_query($conn, $updatereportsearh);
    } else {
        $createreportsearh = "INSERT INTO report (amount, id_faculty, id_department, year)
                    VALUE (1, '$faculty', '$department', NOW())";
        $resultcreate = mysqli_query($conn, $createreportsearh);
    }
} else {
    $sqlquery = "SELECT '===STUDENTINFO===',
    FACULTYNAME
    ,PROGRAMNAME
    ,V_FULLNAME
    ,GPA,
    '===std_master===', m.stdid, avatarimage, codeaccess
    FROM std_master m, AVSREGDW.dbo.studentinfo s
    WHERE s.studentcode = m.stdid and s.GPA LIKE '$gpa%' and FACULTYID != 0 ORDER BY s.GPA DESC";
    if ($gpa != "") {
        if ($rowcheck > 0) {
            $checkstudent = $checkfetch["amount"];
            $updatereportsearh = "UPDATE report SET amount = ($checkstudent+1) where id_faculty = 0 and year = YEAR(CURDATE())";
            $queryresultreport = mysqli_query($conn, $updatereportsearh);
        } else {
            $createreportsearh = "INSERT INTO report (amount, id_faculty, id_department, year)
                    VALUE (1, '$faculty', '$department', NOW())";
            $resultcreate = mysqli_query($conn, $createreportsearh);
        }
    }
}
// sql แสดงข้อมูลนักศึกษา
$getresults = $sqlconn->prepare($sqlquery);
$getresults->execute();
// sql แสดงจำนวนนักศึกษา
$sqlreport = "SELECT COUNT(*) as num_row
                        FROM std_master m, AVSREGDW.dbo.studentinfo s
                        WHERE s.studentcode = m.stdid";
$countstudent = $sqlconn->prepare($sqlreport);
$countstudent->execute();
$rowstudent = $countstudent->fetch(PDO::FETCH_ASSOC);
?>
<!-- Content Row -->
<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xl font-weight-bold text-primary mb-">จำนวนนักศึกษาในระบบ</div>
                        <h5><span class="h5 badge badge-pill badge-success"><?= $rowstudent['num_row']; ?></span></h5>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <?php
                        $queryrandom = "SELECT * FROM report, faculty ORDER BY RAND() LIMIT 2";
                        $resultrandom = mysqli_query($conn, $queryrandom);
                        $random = mysqli_fetch_array($resultrandom);
                        ?>
                        <div class="text-xl font-weight-bold text-primary mb-">คำค้นหาที่น่าสนใจ</div>
                        <?php if ($random == '') : ?>
                            <h5><span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูล</h5>
                        <?php endif; ?>
                        <?php if ($random != '') : ?>
                            <h5><span class="h5 badge badge-pill badge-success"><?= $random['name'] ?></h5>
                            <h5><span class="h5 badge badge-pill badge-success"><?= $random['id_department'] ?></h5>
                        <?php endif; ?>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <?php
                        $amountsearch = "SELECT SUM(amount) AS totalamount FROM report where year = YEAR(CURDATE())";
                        $amountcheck = mysqli_query($conn, $amountsearch);
                        $amount = mysqli_fetch_array($amountcheck);
                        ?>
                        <div class="text-xl font-weight-bold text-primary mb-">การเข้าใช้งานระบบค้นหา</div>
                        <?php if ($amount['totalamount'] == '') : ?>
                            <h5><span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูล</h5>
                        <?php endif; ?>
                        <?php if ($amount['totalamount'] != '') : ?>
                            <h5><span class="h5 badge badge-pill badge-success"><?= $amount['totalamount'] ?></span></h5>
                        <?php endif; ?>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-keyboard fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pending Requests Card Example -->
    <?php
    // sql แสดงจำนวนนักศึกษา
    $sqlcompany = "SELECT COUNT(*) as num_company
                        FROM member
                        WHERE id";
    $resultcompany = mysqli_query($conn, $sqlcompany);
    $countcompany = mysqli_fetch_array($resultcompany);
    ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xl font-weight-bold text-primary mb-">จำนวนบริษัทในระบบ</div>
                        <h5><span class="h5 badge badge-pill badge-success"><?= $countcompany["num_company"]; ?></span></h5>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- ค้นหาข้อมูล -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">ค้นหาข้อมูล</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <form name="search" id="search" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                    <div class="form-group row">
                        <label for="faculty" class="col-md-12">คณะ</label>
                        <div class="col-md-12">
                            <select class="form-control" name="faculty" id="faculty"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="department" class="col-md-12">สาขา</label>
                        <div class="col-md-12">
                            <select class="form-control" name="department" id="department">
                                <option value="" disabled selected>กรุณาเลือกข้อมูลคณะก่อน</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="gpa" class="col-md-12">เกรดเฉลี่ย</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control input" name="gpa" id="gpa">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-primary" id="submitsearch" name="submitsearch" value="ค้นหา" style="margin-top: 10px">
                        </div>
                        <div style="margin-bottom: 88px"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 justify-content-between">
                <div class="row">
                    <div class="col-5">
                        <h6 class="m-0 font-weight-bold text-primary">สถิติคณะที่ถูกเข้าดู</h6>
                    </div>
                    <div class="col-7">
                        <div style="margin-left: 20px">
                            <h6 class="m-0 font-weight-bold text-primary">สถิติสาขาที่ถูกเข้าถูกมากที่สุด</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- สถิติคณะที่ถูกเข้าดู -->
            <div class="row">
                <div class="col-5">
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 1";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">ศิลปศาสตร์</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 2";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">ครุศาสตร์อุตสาหกรรม</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 3";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">เทคโนโลยีการเกษตร</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 4";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">วิศวกรรมศาสตร์</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 5";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">บริหารธุรกิจ</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 6";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">เทคโนโลยีคหกรรมศาสตร์</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 7";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">ศิลปกรรมศาสตร์</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 8";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">เทคโนโลยีสื่อสารมวลชน</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 9";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">วิทยาศาสตร์และเทคโนโลยี</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 10";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">สถาปัตยกรรมศาสตร์</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 11";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">วิทยาลัยการแพทย์แผนไทย</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $facultyquery = "SELECT sum(amount) AS countamount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 12";
                            $fq = mysqli_query($conn, $facultyquery);
                            $amount = mysqli_fetch_array($fq);
                            $numfaculty = $amount['countamount'];
                            if ($numfaculty == '') {
                                $numfaculty = '0';
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary">พยาบาลศาสตร์</h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numfaculty; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- สถิติสาขาที่ถูกเข้าถูกมากที่สุด -->
                <div class="col-7">
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 1 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 2 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 3 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 4 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 5 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 6 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 7 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 8 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 9 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 10 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 11 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $departmentquery = "SELECT id_department, amount FROM report WHERE year = YEAR(CURDATE()) AND id_faculty = 12 ORDER BY amount DESC LIMIT 1";
                            $dq = mysqli_query($conn, $departmentquery);
                            $rowdq = mysqli_num_rows($dq);
                            if ($rowdq > 0) {
                                $amount = mysqli_fetch_array($dq);
                                $vardepartment = $amount['id_department'];
                                $numdepartment = $amount['amount'];
                                if ($vardepartment == '') {
                                    $vardepartment = 'ทุกสาขา';
                                }
                            } else {
                                $vardepartment = "ไม่มีข้อมูลการค้นหา";
                                $numdepartment = "0";
                            }
                            ?>
                            <div class="col-10">
                                <h6 class="m-0 mb-1 font-weight-bold text-Secondary"><?= $vardepartment; ?></h6>
                            </div>
                            <div class="col-2">
                                <span class="h5 badge badge-pill badge-success"><?= $numdepartment; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <!-- รายชื่อนักศึกษา -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">รายชื่อนักศึกษา</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th hidden="true"></th>
                                <th hidden="true"></th>
                                <th hidden="true"></th>
                                <th hidden="true"></th>
                                <th hidden="true"></th>
                                <th hidden="true"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($results = $getresults->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <tr>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img align="left" src="http://app.oreg.rmutt.ac.th/RMUTTStudentProfile/image/student/<?= $results["stdid"]; ?>/<?= $results["avatarimage"]; ?>" style="max-width:15%;height:auto;">
                                                    <div class="row">
                                                        <label class="col-md-12"><?= $results["V_FULLNAME"]; ?></label>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-md-12">คณะ : <span class="badge badge-info"><?= $results["FACULTYNAME"]; ?></span></label>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-md-12">สาขาวิชา : <span class="badge badge-primary"><?= $results["PROGRAMNAME"]; ?></span></label>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-md-12">เกรดเฉลี่ย : <span class="badge badge-pill badge-success"><?= $results["GPA"]; ?></span></label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <form method="post" action="page/detail.php" target="_blank">
                                                                <input type="hidden" id="studentid" name="studentid" value="<?= $results["stdid"]; ?>">
                                                                <input type="hidden" id="qrid" name="qrid" value="<?= $results["codeaccess"]; ?>">
                                                                <input type="submit" class="btn btn-primary" id="detail" name="detail" value="รายละเอียด">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ($_SESSION['userlevel'] == 'user') : ?>
                                                    <div class="card-footer">
                                                        <form method="post" action="index.php?p=appointmentdetail">
                                                            <input type="hidden" id="student_id" name="student_id" value="<?= $results["stdid"]; ?>">
                                                            <input type="submit" class="btn btn-primary" id="appoint" name="appoint" value="นัดหมาย">
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td hidden="true"></td>
                                    <td hidden="true"></td>
                                    <td hidden="true"></td>
                                    <td hidden="true"></td>
                                    <td hidden="true"></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- -----------------------------footer---------------------------- -->
<div class="row">
    <div class="col-xl-12 col-lg-12" style="margin: auto">
        <div class="col-xl-6" style="margin: auto">
            <!-- สถิติของนักศึกษา -->
            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">สถิติของนักศึกษา</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $appointcount = "SELECT student_id, appoint_status FROM appointment WHERE student_id and appoint_status != 'ยกเลิกนัดหมาย'";
                        $resultcount = mysqli_query($conn, $appointcount);
                        $amountappoint = mysqli_num_rows($resultcount);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">จำนวนนักศึกษาที่ถูกนัดหมาย</h6>
                        </div>
                        <?php if ($amountappoint == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($amountappoint != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $amountappoint; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $detailcount = "SELECT amount FROM report WHERE id_faculty = 999";
                        $resultdetail = mysqli_query($conn, $detailcount);
                        $amountdetail = mysqli_fetch_array($resultdetail);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">จำนวนนักศึกษาที่ถูกเข้าดูข้อมูล</h6>
                        </div>
                        <?php if ($amountdetail['amount'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($amountdetail['amount'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $amountdetail['amount']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $sqlstaticaccept = "SELECT amount FROM staticappointment where accept = 'ตอบรับ' and year = YEAR(CURDATE())";
                        $querystaticaccept = mysqli_query($conn, $sqlstaticaccept);
                        $checkstaticaccept = mysqli_fetch_array($querystaticaccept);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">นักศึกษาที่ตอบรับการนัดหมาย</h6>
                        </div>
                        <?php if ($checkstaticaccept['amount'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($checkstaticaccept['amount'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $checkstaticaccept['amount']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $sqlstaticnoaccept = "SELECT amount FROM staticappointment where noaccept = 'ไม่ตอบรับ' and year = YEAR(CURDATE())";
                        $querystaticnoaccept = mysqli_query($conn, $sqlstaticnoaccept);
                        $checkstaticnoaccept = mysqli_fetch_array($querystaticnoaccept);
                        ?>

                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">นักศึกษาที่ไม่ตอบรับการนัดหมาย</h6>
                        </div>
                        <?php if ($checkstaticnoaccept['amount'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($checkstaticnoaccept['amount'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $checkstaticnoaccept['amount']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $sqlstaticcome = "SELECT amount FROM staticappointment where come = 'มาตามการนัดหมาย' and year = YEAR(CURDATE())";
                        $querystaticcome = mysqli_query($conn, $sqlstaticcome);
                        $checkstaticcome = mysqli_fetch_array($querystaticcome);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">นักศึกษาที่มาตามการนัดหมาย</h6>
                        </div>
                        <?php if ($checkstaticcome['amount'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($checkstaticcome['amount'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $checkstaticcome['amount']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $sqlstaticnocome = "SELECT amount FROM staticappointment where nocome = 'ไม่มาตามการนัดหมาย' and year = YEAR(CURDATE())";
                        $querystaticnocome = mysqli_query($conn, $sqlstaticnocome);
                        $checkstaticnoaccept = mysqli_fetch_array($querystaticnocome);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">นักศึกษาที่ไม่มาตามการนัดหมาย</h6>
                        </div>
                        <?php if ($checkstaticnoaccept['amount'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($checkstaticnoaccept['amount'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $checkstaticnoaccept['amount']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $sqlstaticcomeyes = "SELECT amount FROM staticappointment where come_yes = 'มาตามการนัดหมายและตกลงร่วมงาน' and year = YEAR(CURDATE())";
                        $querystaticcomeyes = mysqli_query($conn, $sqlstaticcomeyes);
                        $checkstaticcomeyes = mysqli_fetch_array($querystaticcomeyes);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">นักศึกษาที่ตกลงร่วมงานกับสถานประกอบการ</h6>
                        </div>
                        <?php if ($checkstaticcomeyes['amount'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($checkstaticcomeyes['amount'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $checkstaticcomeyes['amount']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $sqlstaticcomeno = "SELECT amount FROM staticappointment where come_no = 'มาตามการนัดหมายแต่ไม่ตกลงร่วมงาน' and year = YEAR(CURDATE())";
                        $querystaticcomeno = mysqli_query($conn, $sqlstaticcomeno);
                        $checkstaticcomeno = mysqli_fetch_array($querystaticcomeno);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">นักศึกษาที่ไม่ตกลงร่วมงานกับสถานประกอบการ</h6>
                        </div>
                        <?php if ($checkstaticcomeno['amount'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($checkstaticcomeno['amount'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $checkstaticcomeno['amount']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $sqltotalappoint = "SELECT sum(amount) AS totalappointed FROM staticappointment where year = YEAR(CURDATE())
                        and (come = 'มาตามการนัดหมาย' or come_yes = 'มาตามการนัดหมายและตกลงร่วมงาน' or come_no = 'มาตามการนัดหมายแต่ไม่ตกลงร่วมงาน')";
                        $querytotalappoint = mysqli_query($conn, $sqltotalappoint);
                        $checktotalappoint = mysqli_fetch_array($querytotalappoint);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">จำนวนนักศึกษาที่นัดหมายสำเร็จ</h6>
                        </div>
                        <?php if ($checktotalappoint['totalappointed'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($checktotalappoint['totalappointed'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $checktotalappoint['totalappointed']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php
                        $sqltotalnoappoint = "SELECT sum(amount) AS totalnoappointed FROM staticappointment where year = YEAR(CURDATE()) and (noaccept = 'ไม่ตอบรับ' or nocome = 'ไม่มาตามการนัดหมาย')";
                        $querytotalnoappoint = mysqli_query($conn, $sqltotalnoappoint);
                        $checktotalnoappoint = mysqli_fetch_array($querytotalnoappoint);
                        ?>
                        <div class="col-9">
                            <h6 class="m-0 mb-1 font-weight-bold text-Secondary">จำนวนนักศึกษาที่นัดหมายไม่สำเร็จ</h6>
                        </div>
                        <?php if ($checktotalnoappoint['totalnoappointed'] == '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-secondary">ไม่มีข้อมูลสถิติ</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($checktotalnoappoint['totalnoappointed'] != '') : ?>
                            <div class="col-3">
                                <span class="h5 badge badge-pill badge-success"><?= $checktotalnoappoint['totalnoappointed']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ---------------------------------------------------------- -->
<script>
    function $_GETQTY(param) {
        var vars = {};
        window.location.href.replace(location.hash, '').replace(
            /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
            function(m, key, value) { // callback
                vars[key] = value !== undefined ? value : '';
            }
        );

        if (param) {
            return vars[param] ? vars[param] : null;
        }
        return vars;
    }
    $(document).ready(function() {
        load_json_data('faculty');

        function load_json_data(id, parent_id) {
            var datajson = '';
            $.getJSON('./page/app_data/faculty_department.json', function(data) {
                datajson += '<option value="" hidden>ทั้งหมด</option>';
                if (id != 'faculty') datajson += '<option value="">ทั้งหมด</option>';
                $.each(data, function(key, value) {
                    if (id == 'faculty') {
                        if (value.parent_id == '0') {
                            datajson += '<option value="' + value.id + '">' + value.name + '</option>';
                        }
                    } else {
                        if (value.parent_id == parent_id) {
                            datajson += '<option value="' + value.name + '">' + value.name + '</option>';
                        }
                    }
                });
                $('#' + id).html(datajson);
                if ($_GETQTY('faculty') != null) {
                    load_json_data('department', $_GETQTY('faculty'));
                    $("#faculty").val($_GETQTY('faculty'));
                }
                if ($_GETQTY('department') != null && $_GETQTY('faculty') != null)
                    $("#department").val(decodeURIComponent($_GETQTY('department')));
            });
        }

        $(document).on('change', '#faculty', function() {
            var faculty_id = $(this).val();
            if (faculty_id != '') {
                load_json_data('department', faculty_id);
            }
        });
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<?php
$delete = "DELETE FROM appointment WHERE DATEDIFF(date_end,NOW())>7 and appoint_status = 'รอการตอบรับ'";
$querydel = mysqli_query($conn, $delete);
?>