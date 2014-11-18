<?php require_once("db_connect.php");?>
<?php
	if($_POST['submit'])
	{	
		$cookie_name = "user";	
		$username = $_COOKIE[$cookie_name];
		$password = $_POST["oldPassword"];
		$qer1 = "SELECT * FROM Users where username = '{$username}' and password = '{$password}'";
		$res = mysqli_query($db, $qer1);
		$num = mysqli_num_rows($res);
		if($num == 0)
		{
?>		
			<script language="javascript" type="text/javascript">
				window.location = '../userTable.php';
				alert('Failed to delete your account');
			</script>
<?php			
		}
		else
		{
			
			$qer2 = "Delete from Users where username = '{$username}'";
			$result = mysqli_query($db, $qer2);
			

			//time to reset the nfl players to 0
			$reset = "SELECT athlete_name FROM FantasyTeam WHERE username='{$username}'";
			$resetQ = mysqli_query($db, $reset);
			$count = 0;
			while ($row = $resetQ->fetch_assoc()) {
				$name = $row["athlete_name"];
				if($count < 3)
					mysqli_query($db, "UPDATE NFLPlayer SET ASSIGNED=FALSE WHERE NAME='{$name}'");
				if($count < 6)
					mysqli_query($db, "UPDATE NBAPlayer SET ASSIGNED=FALSE WHERE NAME='{$name}'");
				if($count < 9)
					mysqli_query($db, "UPDATE EPLPlayer SET ASSIGNED=FALSE WHERE NAME='{$name}'");
				$count++;
    		}
    		$deleteUser = "DELETE FROM FantasyTeam WHERE username='{$username}'";
    		$deleteTheUser = mysqli_query($db, $deleteUser);
    		if (isset($_COOKIE["user"])) {
            	unset($_COOKIE["user"]);
            }

?>
			<script language="javascript" type="text/javascript">
				window.location = '../index.php';
				alert('Account Succesfully Deleted');
			</script>
<?php		
		}

	}		
?>