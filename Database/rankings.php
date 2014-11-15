<?php require_once("php_actions/db_connect.php");?>
<html>
	<head>
        <title>Stats</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/formstylesheet.css">
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href ="css/rankingStylesheet.css" rel="stylesheet">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/rankings.js"></script>
         <script src = "js/bootstrap.js"></script>
	</head>
	<body>
	<?php
		$array = mysqli_query($db, "SELECT * from Users ORDER BY Points DESC");
		$retData = [];
		while ($row = $array->fetch_assoc()) {
			$retData[] = $row;
    	}
		$username = $_COOKIE["user"];
	?>
	<script type="text/javascript">
		var data = JSON.parse('<?php echo json_encode($retData);?> ');
		var name = '<?php echo $username;?>';
	</script>
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <ul class="nav navbar-nav">
              <li><a href="userTable.php">Your Account</a></li>
              <li><a href='rankings.php'>Rankings</a></li>
              <li><a data-toggle = "modal" href = "#myModal">Change Password</a></li>
              <li><a data-toggle = "modal" href = "#myModal2">Delete Account</a></li>
              <li style="position:right"><a href="index.php">Log Out</a></li>
          </ul>
        </div>
    </nav>
        </nav>
        <div>
			<div class="jumbotron alert-success">
	            <h1>Rankings</h1>
	        </div>	
			<table class="table table-hover" id="mainTable">
	            <thead>
	                <tr bgcolor="#6699FF">
	                	<th>Ranking Number</th>
	                    <th>Username</th>
	                    <th>Total Points</th>
	                </tr>
	            </thead>
	            <tbody>	
	            </tbody>
	        </table>
	    </div>

	<div class = "modal fade" id = "myModal" tabindex = "-1" role = "dialog" aria-hidden = "true">
        <div class = "modal-dialog">
          <div class = "modal-content">
            <div class = "modal-header">
              <h2>Change Password<h2>
            </div>
            <form id="passwordForm" action = "php_actions/ChangePass.php" method = "post">
              <label>Old Password&nbsp;</label>
              <input type="password" name="oldPassword" required>
              <br/>
              <br/>
              <label>New Password</label>
              <input type="password" name="newPassword" required>
              <div class = "modal-footer">
                <input id = "submit" name = "submit" type = "submit" value = "Submit">
                <button type = "button" class = "btn btn-warning" data-dismiss = "modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div class = "modal fade" id = "myModal2" tabindex = "-1" role = "dialog" aria-hidden = "true">
        <div class = "modal-dialog">
          <div class = "modal-content">
            <div class = "modal-header">
              <h2>Delete Account<h2>
            </div>
            <form id="passwordForm" action = "php_actions/delete.php" method = "post">
              <label>Password&nbsp;</label>
              <input type="password" name="oldPassword" required>
            <div class = "modal-footer">
               <input id = "submit" name = "submit" type = "submit" value = "Submit">
              <button type = "button" class = "btn btn-warning" data-dismiss = "modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>

	</body>
</html>