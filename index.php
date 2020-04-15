<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
include 'config/connection.php';
?>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Information Search System</title>
  <link rel="shortcut icon" href="img\1.svg" type="image/x-icon">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <!-- Optional: include a polyfill for ES6 Promises for IE11 -->
  <link rel="stylesheet" href="vendor/alert/sweetalert2.min.css">
</head>

<body id="page-top">
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/alert/sweetalert2.all.min.js"></script>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?p=home">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-search"></i>
        </div>
        <div class="sidebar-brand-text">Information Search System </div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php?p=home">
          <i class="fas fa-home "></i>
          <span>หน้าแรก</span></a>
      </li>
      <?php if (isset($_SESSION['username'])) : ?>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
          เมนูสมาชิก
        </div>
        <!-- Nav Item - Pages Collapse Menu -->
        <?php if ($_SESSION['userlevel'] != 'admin') : ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?p=appointment">
              <i class="far fa-calendar-check"></i>
              <span>จัดการรายการนัดหมาย</span>
            </a>
          </li>
        <?php endif; ?>
        <?php if ($_SESSION['userlevel'] != 'admin') : ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?p=interviewresults">
              <i class="far fa-calendar-check"></i>
              <span>แจ้งผลการสัมภาษณ์</span>
            </a>
          </li>
        <?php endif; ?>
        <?php if ($_SESSION['userlevel'] == 'admin') : ?>
          <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
              <i class="fas fa-fw fa-cog"></i>
              <span>สำหรับผู้ดูแลระบบ</span>
            </a>
            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="index.php?p=manage">จัดการสมาชิก</a>
                <a class="collapse-item" href="index.php?p=allreport">สถิติทั้งหมด</a>
              </div>
            </div>
          </li>
        <?php endif; ?>
      <?php endif; ?>
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <?php if (isset($_SESSION['id'])) : ?>
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
              <!-- Nav Item - Alerts -->
              <?php
              if ($_SESSION['userlevel'] != 'admin') : ?>
                <li class="nav-item dropdown no-arrow mx-1">
                  <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                  <?php endif; ?>
                  <!-- Counter - Alerts -->
                  <?php
                  include 'config/functions.php';
                  if ($_SESSION['userlevel'] == 'student') :
                    $query = "SELECT * FROM notifications WHERE status = 'unread' AND type = 'student' AND name = '{$_SESSION['id']}' ORDER BY date DESC";
                    if (count(fetchAll($query)) > 0) : ?>
                      <span class="badge badge-danger badge-counter">
                        <?php echo count(fetchAll($query)); ?>
                      <?php endif; ?>
                      </span>
                    <?php endif; ?>
                    <?php if ($_SESSION['userlevel'] == 'user') :
                      $query = "SELECT * FROM notifications WHERE status = 'unread' AND type = 'user' AND name = '{$_SESSION['id']}' ORDER BY date DESC";
                      if (count(fetchAll($query)) > 0) : ?>
                        <span class="badge badge-danger badge-counter">
                          <?php echo count(fetchAll($query)); ?>
                        <?php endif; ?>
                        </span>
                      <?php endif; ?>
                  </a>
                  <!-- Dropdown - Alerts -->
                  <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                      <div class="small" style="font-size:15px">แจ้งเตือน</div>
                    </h6>
                    <?php
                    if ($_SESSION['userlevel'] == 'user') :
                      $query = "SELECT * FROM notifications WHERE type = 'user' AND name = '{$_SESSION['id']}' ORDER BY date DESC";
                      if (count(fetchAll($query)) > 0) : ?>
                        <?php
                        foreach (fetchAll($query) as $i) : ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'user' && $i['message'] == 'นักศึกษาตอบรับการนัดหมายของคุณ' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=appointment&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "มีตอบรับการนัดหมายของคุณ"; ?>
                            </a>
                          <?php endif; ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'user' && $i['message'] == 'ตกลงร่วมงาน' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=interviewresults&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "นึกศึกษาตกลงร่วมงานกับคุณ"; ?>
                            </a>
                          <?php endif; ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'user' && $i['message'] == 'ไม่ตกลงร่วมงาน' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=interviewresults&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "นึกศึกษาไม่ตกลงร่วมงานกับคุณ"; ?>
                            </a>
                          <?php endif; ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'user' && $i['message'] == 'ไม่ตอบรับ' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=appointment&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "นักศึกษาไม่ตอบรับคำขอนัดหมายของคุณ"; ?>
                            </a>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      <?php $querysql = "SELECT * from notifications WHERE type = 'user' AND name = '{$_SESSION['id']}'";
                      $resultquery = mysqli_query($conn, $querysql);
                      $resultstatus = mysqli_fetch_array($resultquery);
                      if ($resultstatus['status'] == 'read' || $resultstatus['status'] == NULL) :  ?>
                        <a style="font-size:15px;" ; class="dropdown-item">
                          <small><i>ไม่มีรายการแจ้งเตือนใหม่</i></small>
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>
                    <?php
                    if ($_SESSION['userlevel'] == 'student') :
                      $query = "SELECT * FROM notifications WHERE type = 'student' AND name = '{$_SESSION['id']}' order by date DESC";
                      if (count(fetchAll($query)) > 0) : ?>
                        <?php
                        foreach (fetchAll($query) as $i) : ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'student' && $i['message'] == 'มีรายการนัดหมายถึงคุณ' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=appointment&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "มีรายการนัดหมายถึงคุณ"; ?>
                            </a>
                          <?php endif; ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'student' && $i['message'] == 'มีข้อมูลการนัดหมายถึงคุณ' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=appointment&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "มีข้อมูลการนัดหมายถึงคุณ"; ?>
                            </a>
                          <?php endif; ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'student' && $i['message'] == 'แจ้งผลผ่านการสัมภาษณ์' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=interviewresults&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "แจ้งผลผ่านการสัมภาษณ์"; ?>
                            </a>
                          <?php endif; ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'student' && $i['message'] == 'ไม่ผ่านการสัมภาษณ์' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=interviewresults&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "แจ้งผลไม่ผ่านการสัมภาษณ์"; ?>
                            </a>
                          <?php endif; ?>
                          <?php if ($i['status'] == 'unread' && $i['type'] == 'student' && $i['message'] == 'ไม่มาสัมภาษณ์' && $i['name'] == "{$_SESSION['id']}") : ?>
                            <a style="font-weight:bold;" ; class="dropdown-item" href="index.php?p=interviewresults&id=<?php echo $i['id'] ?>">
                              <small><i><?php echo date('F j, Y', strtotime($i['date'])) ?></i></small><br>
                              <?php
                              echo "แจ้งผลไม่มาสัมภาษณ์"; ?>
                            </a>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      <?php
                      $querysql = "SELECT * FROM notifications WHERE type = 'student' AND name = '{$_SESSION['id']}'";
                      $resultquery = mysqli_query($conn, $querysql);
                      $resultstatus = mysqli_fetch_array($resultquery);
                      if ($resultstatus['status'] == 'read' || $resultstatus['status'] == NULL) :  ?>
                        <a style="font-size:15px;" ; class="dropdown-item">
                          <small><i>ไม่มีรายการแจ้งเตือนใหม่</i></small>
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                </li>
                <!-- Nav Item - Messages -->

                <div class="topbar-divider d-none d-sm-block"></div>
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-lg-inline text-gray-600 small"><?= $_SESSION['username']; ?></span>
                  </a>
                  <!-- Dropdown - User Information -->
                  <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <?php if ($_SESSION['userlevel'] == 'user') : ?>
                      <a class="dropdown-item" href="index.php?p=editprofile">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        จัดการบัญชีผู้ใช้งาน
                      </a>
                      <div class="dropdown-divider">
                      </div><?php endif; ?>
                    <a class="dropdown-item" href="index.php?p=logout">
                      <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                      ออกจากระบบ
                    </a>
                  </div>
                </li>

            </ul>
          <?php else : ?>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small">เข้าสู่ระบบ / ลงทะเบียน</span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    เข้าสู่ระบบ
                  </a>
                  <a class="dropdown-item" href="index.php?p=register">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    ลงทะเบียน
                  </a>
                </div>
              </li>
            </ul>
          <?php endif; ?>
          <!-- Logout Modal-->
          <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">ลงชื่อเข้าใช้งานระบบ</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <section id="tabs" class="project-tab">
                    <div class="container">
                      <div class="row">
                        <div class="col-md-12">
                          <nav>
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                              <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">สำหรับผู้ใช้</a>
                              <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">สำหรับนักศึกษา</a>
                              <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">สำหรับผู้ดูแลระบบ</a>
                            </div>
                          </nav>
                          <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                              <form name="login" id="login" action="index.php?p=login&type=member" method="post" autocomplete="off">
                                <div class="form-group mt-3">
                                  <input type="text" class="form-control" id="username" name="username" placeholder="ชื่อผู้ใช้" pattern="[a-zA-Z0-9]+" minlength="4" maxlength="15" placeholder="ชื่อผู้ใช้" title="กรุณากรอกตัวอักษรภาษาอังกฤษหรือตัวเลขเท่านั้น" required />
                                </div>
                                <div class="form-group">
                                  <input type="hidden" id="type" name="type" value="member">
                                  <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" required />
                                </div>
                                <input type="submit" ID="login" class="btn btn-primary btn-block" value="เข้าสู่ระบบ" />
                                <div class="form-footer mt-2">
                                  <a href="index.php?p=forgotpassword">ลืมรหัสผ่าน?</a>
                                </div>
                              </form>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                              <form name="login" id="login" action="index.php?p=login&type=student" method="post" autocomplete="off">
                                <div class="form-group mt-3">
                                  <input type="text" class="form-control" id="username" name="username" placeholder="รหัสนักศึกษา" required />
                                </div>
                                <div class="form-group">
                                  <input type="hidden" id="type" name="type" value="student">
                                  <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" required />
                                </div>
                                <input type="submit" ID="login" class="btn btn-primary btn-block" value="เข้าสู่ระบบ" />
                              </form>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                              <form name="login" id="login" action="index.php?p=login&type=admin" method="post" autocomplete="off">
                                <div class="form-group mt-3">
                                  <input type="text" class="form-control" id="username" name="username" placeholder="ชื่อผู้ใช้" pattern="[a-zA-Z0-9]+" minlength="4" maxlength="15" placeholder="ชื่อผู้ใช้" title="กรุณากรอกตัวอักษรภาษาอังกฤษหรือตัวเลขเท่านั้น" required />
                                </div>
                                <div class="form-group">
                                  <input type="hidden" id="type" name="type" value="admin">
                                  <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" required />
                                </div>
                                <input type="submit" ID="login" class="btn btn-primary btn-block" value="เข้าสู่ระบบ" />
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <?php
          if ($_GET["p"] == "home")
            include("page/home.php");
          else if ($_GET["p"] == "login")
            include("page/login.php");
          else if ($_GET["p"] == "logout")
            include("page/logout.php");
          else if ($_GET["p"] == "appointmentdetail")
            include("page/appointmentdetail.php");
          else if ($_GET["p"] == "appointment")
            include("page/appointment.php");
          else if ($_GET["p"] == "chat")
            include("page/chat_box.php");
          else if ($_GET["p"] == "register")
            include("page/register.php");
          else if ($_GET["p"] == "register_action")
            include("page/register_action.php");
          else if ($_GET["p"] == "editprofile")
            include("page/editprofile.php");
          else if ($_GET["p"] == "interviewresults")
            include("page/interviewresults.php");
          else if ($_GET["p"] == "updateinterview")
            include("page/updateinterview.php");
          else if ($_GET["p"] == "updatestatus")
            include("page/updatestatus.php");
          else if ($_GET["p"] == "createappoint")
            include("page/createappoint.php");
          else if ($_GET["p"] == "forgotpassword")
            include("page/forgotpassword.php");
          else if ($_GET["p"] == "forgotaction")
            include("page/forgotaction.php");
          else if ($_GET["p"] == "resetpassword")
            include("page/resetpassword.php");
          else if ($_GET["p"] == "allreport")
            include("page/allreport.php");
          else if ($_GET["p"] == "manage")
            include("page/manage.php");
          else if ($_GET["p"] == "updateuser")
            include("page/updateuser.php");
          else
            include("page/home.php");
          ?>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright 2013-2019 Blackrock Digital LLC. Code released under the MIT license.</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->

  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>