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
								  SELECT SUM(NFLPlayer.TD) as NFLTDs, (SELECT SUM(NFLPlayer.YDS) as QBYds FROM NFLPlayer WHERE POSITION='QB'), (SELECT SUM(NFLPlayer.YDS) as WRYds FROM NFLPlayer WHERE POSITION='WR'), (SELECT SUM(NFLPlayer.YDS) as RBYds FROM NFLPlayer WHERE POSITION='RB'), (SELECT SUM(EPLPlayer.GOALS) FROM EPLPlayer) as EPLGoals, (SELECT SUM(EPLPlayer.ASSISTS) FROM EPLPlayer) as EPLAssists, (SELECT SUM(NBAPlayer.POINTS) FROM NBAPlayer) as NBAPoints, (SELECT SUM(NBAPlayer.ASSISTS) FROM NBAPlayer) as NBAAssists, (SELECT SUM(NBAPlayer.REBOUNDS) FROM NBAPlayer) as NBARebounds FROM NFLPlayer");
	} 
	else
		echo "failed to update TotalStats";

	//Time to update all the points
	$result = mysqli_query($db, "SELECT * FROM TotalStats");
	$result = $result->fetch_assoc();
	$nums = [];
	$nums [] = $result["NFLTds"];
	$nums [] = $result["QBYds"];
	$nums [] = $result["WRYds"];
	$nums [] = $result["RBYds"];
	$nums [] = $result["EPLGoals"];
	$nums [] = $result["EPLAssists"];
	$nums [] = $result["NBAPoints"];
	$nums [] = $result["NBAAssists"];
	$nums [] = $result["NBARebounds"];
	$min = $nums[0];
	for($i = 0; $i < 9; $i++)
	{
		if($nums[$i] < $min)
			$min = $nums[$i];
	}
	//Get the conversion ratios
	$NFLTds = 3*$min/$nums[0];
	$QBYds = $min/$nums[1];
	$WRYds = $min/$nums[2];
	$RBYds = $min/$nums[3];
	$EPLGoals = 3*$min/$nums[4];
	$EPLAssists = 3*$min/$nums[5];
	$NBAPoints = 3*$min/$nums[6];
	$NBAAssists = 3*$min/$nums[7];
	$NBARebounds = 3*$min/$nums[8];

	$result = mysqli_query($db, "SELECT * FROM FantasyTeam");

	while ($row = $result->fetch_assoc())
	{
    	$user = $row["username"]; //gets the username
    	$sport = $row["sport"];
    	$name=  $row["athlete_name"];
    	if(strcmp("NFL", $sport) == 0){
    		$points = mysqli_query($db, "SELECT TD, YDS, POSITION FROM NFLPlayer WHERE NAME = '{$name}'");
    		$points = $points->fetch_assoc();
    		if ($points['POSITION'] == 'QB')
    			$NFLpoints = $NFLTds * $points["TD"] + $QBYds*$points["YDS"];
    		else if ($points['POSITION'] == 'WR')
    			$NFLpoints = $NFLTds * $points["TD"] + $WRYds*$points["YDS"];
    		else
    			$NFLpoints = $NFLTds * $points["TD"] + $RBYds*$points["YDS"];
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