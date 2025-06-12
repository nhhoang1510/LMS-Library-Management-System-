<?php
	session_start();
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$password = "";
	$query = "select * from admins where admin_email = '$_SESSION[admin_email]'";
	$query_run = mysqli_query($connection,$query);
	while ($row = mysqli_fetch_assoc($query_run)){
		$password = $row['admin_password'];
	}
	if($password == $_POST['old_password']){
		$query = "update admins set admin_password = '$_POST[new_password]' where admin_email = '$_SESSION[admin_email]'";
		$query_run = mysqli_query($connection,$query);
		?>
		<script type="text/javascript">
			alert("Cập nhật mật khẩu thành công");
			window.location.href = "admin_dashboard.php";
		</script>
		<?php
	}
	else{
		?>
		<script type="text/javascript">
			alert("Vui lòng kiểm tra lại mật khẩu");
			window.location.href = "change_password.php";
		</script>
		<?php
	}
?>
