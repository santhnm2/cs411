<?php require_once("db_connect.php");?>
<?php
	if(isset($_POST['submit']))
	{
		$username =  $_POST["username"];
		$password =  $_POST["password"];

		$cookie_name = "user";
		$cookie_value = $username;
		setcookie($cookie_name, $cookie_value, time() + 3600, "/");

		$qer = "SELECT * FROM Users where username = '{$username}' and password = '{$password}'";
		$res = mysqli_query($db, $qer);
		$num = mysqli_num_rows($res);
		if($num == 0)
		{
			
?>
			<script language="javascript" type="text/javascript">
				alert('Invalid Login');
				window.location = '../index.php';
			</script>
			
<?php			
		}
			
		else
		{
			header("Location: " . "../userTable.php");
		}
	}
?>
