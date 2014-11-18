<?php
	require_once("db_connect.php");
	//update NFL Player Table
	$result = mysqli_query($db, "DELETE FROM NFLPlayer2");
 	if($result)
 	{
	 	$fp = fopen("nfl.csv", "r");
		while(!feof($fp) ) {
	 		$line = fgetcsv($fp, 10000, "|");
	   		$importSQL = "INSERT INTO NFLPlayer2 VALUES('".$line[0]."','".$line[1]."', '".$line[2]."', '".$line[3]."', '".$line[4]."', '".$line[5]."', '".$line[6]."', '".$line[7]."', '".$line[8]."')";
	    	mysqli_query($db, $importSQL); 
	 	}
	 	fclose($fp);
	 	$res = mysqli_query($db, "UPDATE NFLPlayer n1, NFLPlayer2 n2 SET n1.TD = n2.TD, n1.YDS = n2.YDS WHERE n1.NAME = n2.NAME AND n1.JERSEY = n2.JERSEY");
	 	if(!$res)
	 		echo "Failed to update NBAPlayer";
	 }
	 else
	 	echo "Failed to delete the Football Table";

	//updates the NBA Player Table
	$result = mysqli_query($db, "DELETE FROM NBAPlayer2");
	if($result)
	{
		$fp = fopen("nba.csv", "r");
		while(!feof($fp) ) {
	 		$line = fgetcsv($fp, 10000, "|");
	   		$importSQL = "INSERT INTO NBAPlayer2 VALUES('".$line[0]."','".$line[1]."', '".$line[2]."', '".$line[3]."', '".$line[4]."', '".$line[5]."', '".$line[6]."', '".$line[7]."', '".$line[8]."', '".$line[9]."')";
	    	mysqli_query($db, $importSQL); 
	 	}
	 	fclose($fp);
	 	$res = mysqli_query($db, "UPDATE NBAPlayer n1, NBAPlayer2 n2 SET n1.POINTS = n2.POINTS, n1.ASSISTS = n2.ASSISTS, n1.REBOUNDS = n2.REBOUNDS WHERE n1.NAME = n2.NAME AND n1.JERSEY = n2.JERSEY");
	 	if(!$res)
	 		echo "Failed to update NBAPlayer";
	}
	else
		echo "Failed to Delete the Basketball Table";

	//Update the EPL Player Table
	$result = mysqli_query($db, "DELETE FROM EPLPlayer2");
	if($result) 
	{
		$fp = fopen("epl.csv", "r");
		while(!feof($fp) ) {
	 		$line = fgetcsv($fp, 10000, "|");
	 		$temp = str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $line[1]); 
	   		$importSQL = "INSERT INTO EPLPlayer2 VALUES('".$line[0]."','".$temp."', '".$line[2]."', '".$line[3]."', '".$line[4]."', '".$line[5]."', '".$line[6]."', '".$line[7]."', '".$line[8]."')";
	    	mysqli_query($db, $importSQL); 
	 	}
	 	fclose($fp);
	 	$res = mysqli_query($db, "UPDATE EPLPlayer n1, EPLPlayer2 n2 SET n1.goals = n2.goals, n1.assists = n2.assists WHERE n1.name = n2.name AND n1.jerseyNumber = n2.jerseyNumber");
	 	if(!$res)
	 		echo "Failed to update NBAPlayer";
	}
	else
		echo "Failed to delete the Soccer Table;";


	//Update the total stats table
	$result = mysqli_query($db, "DELETE FROM TotalStats");
	if($result)
	{
		$res = mysqli_query($db, "INSERT INTO TotalStats
								  SELECT SUM(NFLPlayer.TD) as NFLTDs, SUM(NFLPlayer.YDS) as NFLYds, (SELECT SUM(EPLPlayer.GOALS) FROM EPLPlayer) as EPLGoals, (SELECT SUM(EPLPlayer.ASSISTS) FROM EPLPlayer) as EPLAssists, (SELECT SUM(NBAPlayer.POINTS) FROM NBAPlayer) as NBAPoints, (SELECT SUM(NBAPlayer.ASSISTS) FROM NBAPlayer) as NBAAssists, (SELECT SUM(NBAPlayer.REBOUNDS) FROM NBAPlayer) as NBARebounds FROM NFLPlayer");
	} 
	else
		echo "failed to update TotalStats";

	//Time to update all the points
	$result = mysqli_query($db, "SELECT * FROM TotalStats");
	$result = $result->fetch_assoc();
	$nums = [];
	$nums [] = $result["NFLTds"];
	$nums [] = $result["NFLYds"];
	$nums [] = $result["EPLGoals"];
	$nums [] = $result["EPLAssists"];
	$nums [] = $result["NBAPoints"];
	$nums [] = $result["NBAAssists"];
	$nums [] = $result["NBARebounds"];
	$min = $nums[0];
	for($i = 0; $i < 7; $i++)
	{
		if($nums[$i] < $min)
			$min = $nums[$i];
	}
	//Get the conversion ratios
	$NFLTds = $min/$nums[0];
	$NFLYds = $min/$nums[1];
	$EPLGoals = $min/$nums[2];
	$EPLAssists = $min/$nums[3];
	$NBAPoints = $min/$nums[4];
	$NBAAssists = $min/$nums[5];
	$NBARebounds = $min/$nums[6];

	$result = mysqli_query($db, "SELECT * FROM FantasyTeam");

	while ($row = $result->fetch_assoc())
	{
    	$user = $row["username"]; //gets the username
    	$sport = $row["sport"];
    	$name=  $row["athlete_name"];
    	if(strcmp("NFL", $sport) == 0){
    		$points = mysqli_query($db, "SELECT TD, YDS FROM NFLPlayer WHERE NAME = '{$name}'");
    		$points = $points->fetch_assoc();
    		$NFLpoints = $NFLTds * $points["TD"] + $NFLYds*$points["YDS"];
    		mysqli_query($db, "UPDATE FantasyTeam SET athlete_points = '{$NFLpoints}' WHERE athlete_name = '{$name}'");
    	}
    	else if (strcmp("NBA", $sport) == 0){
    		$points = mysqli_query($db, "SELECT POINTS, ASSISTS, REBOUNDS FROM NBAPlayer WHERE NAME = '{$name}'");
    		$points = $points->fetch_assoc();
    		$NBApoints = $NBAPoints * $points["POINTS"] + $NBAAssists*$points["ASSISTS"] + $NBARebounds*$points["REBOUNDS"];
    		mysqli_query($db, "UPDATE FantasyTeam SET athlete_points = '{$NBApoints}' WHERE athlete_name = '{$name}'");
    	}
    	else if (strcmp("EPL", $sport) == 0){
    		$points = mysqli_query($db, "SELECT goals, assists FROM EPLPlayer WHERE NAME = '{$name}'");
    		$points = $points->fetch_assoc();
    		$EPLpoints = $EPLGoals * $points["goals"] + $EPLAssists*$points["assists"];
    		mysqli_query($db, "UPDATE FantasyTeam SET athlete_points = '{$EPLpoints}' WHERE athlete_name = '{$name}'");
    	}
	}
	$result = mysqli_query($db, "SELECT username FROM Users");
	while($row = $result->fetch_assoc()){
		$username = $row["username"];
		$query = "SELECT SUM(athlete_points) FROM FantasyTeam WHERE username = '{$username}'";
		$res = mysqli_query($db, $query);
		$temp = $res->fetch_assoc();
		$points = $temp["SUM(athlete_points)"];
		$query = "UPDATE Users set Points = '{$points}' WHERE username = '{$username}'";
		mysqli_query($db, $query);
	}


?>