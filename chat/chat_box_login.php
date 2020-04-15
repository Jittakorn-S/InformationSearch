<?php
session_start();
if(isset($_POST['btn_login'])){
	$_SESSION['ses_user_id']=$_POST['userID'];
}
if(isset($_POST['btn_logout'])){
	unset($_SESSION['ses_user_id']);
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>chat box</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">   
</head>

<body>


<br>
<?php if(isset($_SESSION['ses_user_id']) && $_SESSION['ses_user_id']!=""){?>
<form name="form2" method="post" action="">
  <input type="submit" name="btn_logout" id="btn_logout" value="Logout ลบ session">
</form>
<?php }else{ ?>
<form name="form2" method="post" action="">
  <select name="userID" id="userID">
  	<option value="1">User 1</option>      
  	<option value="2">User 2</option>    
  	<option value="3">User 3</option>   
  	<option value="4">User 4</option>           
  </select>
  <input type="submit" name="btn_login" id="btn_login" value="Login สร้าง session">
</form>
<?php } ?>
<br>
<br>
<?php if(isset($_SESSION['ses_user_id']) && $_SESSION['ses_user_id']!=""){?>
<form name="form1" method="post" action="chat_box_apply.php">
  <select name="userID" id="userID">
  	<option value="1">User 1</option>      
  	<option value="2">User 2</option>    
  	<option value="3">User 3</option>   
  	<option value="4">User 4</option>           
  </select>
  <input type="submit" name="set_user1" value="Chat with this user">
</form>

<?php } ?>
<br>
<br>

</body>
</html>