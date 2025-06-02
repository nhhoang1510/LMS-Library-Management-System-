<?php
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$query = "update users set name = '$_POST[name]',email = '$_POST[email]',mobile = '$_POST[mobile]',address = '$_POST[address]'";
	$query_run = mysqli_query($connection,$query);
?>
<script type="text/javascript">
	alert("Cập nhật thông tin thành công");
	window.location.href = "user_dashboard.php";
</script>