<?php
	 $user = 'rmukerj2_fan';
	 $pass = 'test1234';
	 $db_name = 'rmukerj2_fantasy';
	 $db = new mysqli('engr-cpanel-mysql.engr.illinois.edu', $user, $pass, $db_name);
	    if ($db->connect_errno) 
	    {
		    echo "Connection failed!";
		    exit();
	    }
?>
