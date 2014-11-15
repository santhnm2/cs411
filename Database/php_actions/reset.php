<?php require_once("db_connect.php");?>
<?php
	if($_POST['submit'])
	{		
		mysqli_query($db, "UPDATE NFLPlayer set ASSIGNED = FALSE");
		mysqli_query($db, "UPDATE NBAPlayer set ASSIGNED = FALSE");
		mysqli_query($db, "DELETE FROM FantasyTeam");
		mysqli_query($db, "DELETE FROM Users");
		header("Location: " . "../index.php");
	}
?>