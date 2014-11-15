<?php require_once("db_connect.php");
	if($_POST['submit'])
	{		
		$email =  $_POST["email"];
		$username = $_POST["username"];
		$password =  $_POST["password"];
		$confirm = $_POST["confirm_password"];
		if($password != $confirm)
		{
?>
			<script language="javascript" type="text/javascript">
				alert('Passwords Do Not Match.');
				window.location = '../index.php';
			</script>
<?php			
			exit;
		}
		$qer = "INSERT INTO Users (email, username, password) VALUES ('{$email}', '{$username}', '{$password}')";
		$result = mysqli_query($db, $qer);

		if($result)
		{
			//Send a confirmation email to the user. Do this once
			//mail($email, 'Welcome to Fantasy Frenzy!, 'Hello, 'From: FantasyFrenzy');
			$players = [];
			$qb_array = [];
			$count = 0;
			$query = "SELECT * FROM NFLPlayer WHERE POSITION = 'QB' AND ASSIGNED = FALSE";
			$qb_result = mysqli_query($db, $query);
			while ($row = $qb_result->fetch_assoc()) {
	        	$qb_array[] = $row["NAME"];
	        	$count++;
	    	}
	    	$rand = rand(0, $count - 1);
	    	$players[] = $qb_array[$rand];
	    	$qb = $players[0];
	    	$update_qb = "UPDATE NFLPlayer SET ASSIGNED = TRUE WHERE NAME = '{$qb}' LIMIT 1";
	    	$update_qb_result = mysqli_query($db, $update_qb);

	    	//assign user random runningback
	    	$rb_array = [];
			$count = 0;
			$query = "SELECT * FROM NFLPlayer WHERE POSITION = 'RB' AND ASSIGNED = FALSE";
			$rb_result = mysqli_query($db, $query);
			while ($row = $rb_result->fetch_assoc()) {
	        	$rb_array[] = $row["NAME"];
	        	$count++;
	    	}
	    	$rand = rand(0, $count - 1);
	    	$players[] = $rb_array[$rand];
			$rb = $players[1];
	    	$update_rb = "UPDATE NFLPlayer SET ASSIGNED = TRUE WHERE NAME = '{$rb}' LIMIT 1";
	    	$update_rb_result = mysqli_query($db, $update_rb);

	    	//assign user random widereceiver
	    	$wr_array = [];
			$count = 0;
			$query = "SELECT * FROM NFLPlayer WHERE POSITION = 'WR' AND ASSIGNED = FALSE";
			$wr_result = mysqli_query($db, $query);
			while ($row = $wr_result->fetch_assoc()) {
	        	$wr_array[] = $row["NAME"];
	        	$count++;
	    	}
	    	$rand = rand(0, $count - 1);
	    	$players[] = $wr_array[$rand];
	    	$wr = $players[2];
	    	$update_wr = "UPDATE NFLPlayer SET ASSIGNED = TRUE WHERE NAME = '{$wr}' LIMIT 1";
	    	$update_wr_result = mysqli_query($db, $update_wr);

	    	//put the players under the username
	    	foreach ($players as &$val)
	  		{
	  			$team = mysqli_query($db, "SELECT * FROM NFLPlayer where NAME = '{$val}' LIMIT 1");
	  			$temp = $team->fetch_assoc();
	  			$team_name =  $temp["TEAM"];
	  			$team_pos = $temp["POSITION"];
	  			$sum = $temp["TD"] + $temp["YDS"];
	    		$quer = "INSERT INTO FantasyTeam VALUES('{$username}', '{$val}', '{$team_pos}' , '{$sum}', '{$team_name}', 'NFL')";
	    		$res =  mysqli_query($db, $quer); 
	    	}


	    	/*Add BasketBall Players*/
	    	$players = [];
			$count = 0;
			$query = "SELECT * FROM NBAPlayer WHERE ASSIGNED = FALSE";
			$result = mysqli_query($db, $query);
			while ($row = $result->fetch_assoc()) {
	        	$nba_array[] = $row["NAME"];
	        	$count++;
	    	}
	    	$checkDif= false;
	    	while(!$checkDif){
	    		$firstRandom = rand(0, $count-1);
	    		$secondRandom = rand(0,$count-1);
	    		$thirdRandom = rand(0, $count-1);
	    		if($firstRandom != $secondRandom && $firstRandom != $thirdRandom && $secondRandom != $thirdRandom)
	    			$checkDif = true;
	    	}
	    	$players[] = $nba_array[$firstRandom];
	    	$players[] = $nba_array[$secondRandom];
	    	$players[] = $nba_array[$thirdRandom];
	    	for($i = 0; $i < 3; $i++){
	    		$array_temp = $players[$i];
	    		$update_nba = "UPDATE NBAPlayer SET ASSIGNED = TRUE WHERE NAME = '{$array_temp}' LIMIT 1";
	    		$update_wr_result = mysqli_query($db, $update_nba);
	    		$team = mysqli_query($db, "SELECT * FROM NBAPlayer where NAME = '{$array_temp}' LIMIT 1");
	  			$temp = $team->fetch_assoc();
	  			$team_name =  $temp["TEAM"];
	  			$team_pos = $temp["POSITION"];
	  			$sum = $temp["POINTS"] + $temp["REBOUNDS"] + $temp["ASSISTS"];
	    		$quer = "INSERT INTO FantasyTeam VALUES('{$username}', '{$array_temp}', '{$team_pos}', '{$sum}', '{$team_name}', 'NBA')";
	    		$res =  mysqli_query($db, $quer); 
	    	}



	    	//time to sum up the points
	    	$query = "SELECT SUM(athlete_points) FROM FantasyTeam WHERE username = '{$username}'";
	    	$res = mysqli_query($db, $query);
	    	$temp = $res->fetch_assoc();
	    	$points = $temp["SUM(athlete_points)"];
	    	$query = "UPDATE Users set Points = '{$points}' WHERE username = '{$username}'";
	    	mysqli_query($db, $query);
?>
			<script language="javascript" type="text/javascript">
				alert('Your account has been created.');
				window.location = '../index.php';
			</script>
<?php
	    }
		else
		{
?>
			<script language="javascript" type="text/javascript">
				alert('The username you have entered already exists.');
				window.location = '../index.php';
			</script>
<?php
		}
	}
?>