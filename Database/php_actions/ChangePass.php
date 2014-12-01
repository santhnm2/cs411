<?php require_once("db_connect.php");?>
<?php
	if($_POST['submit'])
	{
		$cookie_name = "user";		
		$username = $_COOKIE[$cookie_name];
		$password =  $_POST["oldPassword"];
		$hashedPass = md5($password);
		$new_pass = $_POST["newPassword"];
		$confirm =  $_POST["confirm"];
		
		$qer1 = "SELECT * FROM Users where username = '{$username}' and password = '{$hashedPass}'";
		$res = mysqli_query($db, $qer1);
		$num = mysqli_num_rows($res);
		if($num == 0)
		{
?>		
			<script language="javascript" type="text/javascript">
				window.location = '../userTable.php';
				alert('Incorrect Old Password');
			</script>
<?php			
		}
		else if($new_pass != $confirm)
		{
?>

			<script language="javascript" type="text/javascript">
				window.location = '../userTable.php';
				alert('New Passwords Do Not Match.');
			</script>	
<?php		
		}
		else
		{
			$newHashedPass = md5($new_pass);
			$qer2 = "UPDATE Users set password = '{$newHashedPass}' WHERE username = '{$username}'";
			$result = mysqli_query($db, $qer2);
?>	
			<script language="javascript" type="text/javascript">
				window.location = '../userTable.php';
				alert('Password Change Succesful!');
			</script>	
<?php
		}

	}		
?>