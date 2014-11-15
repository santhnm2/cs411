<?php
	require_once("db_connect.php");

	//update NFL Player Table
	$result = mysqli_query($db, "SELECT * FROM NFLPlayer2");
 	if(mysqli_num_rows($result) == 0)
 	{
	 	$fp = fopen("nfl.csv", "r");
		while(!feof($fp) ) {
	 		$line = fgetcsv($fp, 10000, "|");
	   		$importSQL = "INSERT INTO NFLPlayer2 VALUES('".$line[0]."','".$line[1]."', '".$line[2]."', '".$line[3]."', '".$line[4]."', '".$line[5]."', '".$line[6]."', '".$line[7]."', '".$line[8]."')";
	    	mysqli_query($db, $importSQL); 
	 	}
	 	fclose($fp);
	 }
	 else
	 	echo "football already exists";

	//updates the NBA Player Table
	$result = mysqli_query($db, "SELECT * FROM NBAPlayer2");
	if(mysqli_num_rows($result) == 0)
	{
		$fp = fopen("nba.csv", "r");
		while(!feof($fp) ) {
	 		$line = fgetcsv($fp, 10000, "|");
	   		$importSQL = "INSERT INTO NBAPlayer2 VALUES('".$line[0]."','".$line[1]."', '".$line[2]."', '".$line[3]."', '".$line[4]."', '".$line[5]."', '".$line[6]."', '".$line[7]."', '".$line[8]."', '".$line[9]."')";
	    	mysqli_query($db, $importSQL); 
	 	}
	 	fclose($fp);
	}
	else
		echo "basketball already exists";

?>