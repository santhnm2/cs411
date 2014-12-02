<?php require_once("php_actions/db_connect.php");?>
<html>
  <head>
        <title>Stats</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/formstylesheet.css">
        <link type="text/css" href ="css/table_stylesheet.css" rel="stylesheet">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/userTable.js"></script>
        <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src = "js/bootstrap.js"></script>
  </head>
  <body>
    <?php
      $cookie_name = "user";    
      $username = $_COOKIE[$cookie_name];
      $array = (mysqli_query($db, "SELECT * from FantasyTeam where username = '{$username}' ORDER BY athlete_points DESC"));
      $retData2 = [];
      $urlsNFL = (mysqli_query($db, "SELECT n1.URL from NFLPlayer n1, FantasyTeam f1 where n1.NAME = f1.athlete_name and f1.username = '{$username}'"));
      $urlsNBA = (mysqli_query($db, "SELECT n1.URL from NBAPlayer n1, FantasyTeam f1 where n1.NAME = f1.athlete_name and f1.username = '{$username}'"));
      $urlsEPL = (mysqli_query($db, "SELECT n1.URL from EPLPlayer n1, FantasyTeam f1 where n1.name = f1.athlete_name and f1.username = '{$username}'"));
      while ($row1 = $urlsNFL->fetch_assoc()) {
        $retData2[] = $row1;
      }
      while ($row1 = $urlsNBA->fetch_assoc()) {
        $retData2[] = $row1;
      }
      while ($row1 = $urlsEPL->fetch_assoc()) {
        $retData2[] = $row1;
      }
      $retData = [];
      while ($row = $array->fetch_assoc()) {
        $retData[] = $row;
      }
      for($i = 0; $i < sizeof($retData);$i++){
        if($retData[$i]['sport'] == "NFL"){
          $athlete = $retData[$i]['athlete_name'];
          $stats = mysqli_query($db, "SELECT YDS, TD from NFLPlayer where NAME = '{$athlete}'");
          $stats = $stats->fetch_assoc();
          $retData[$i]['stats'] = $stats;
        }
        else if($retData[$i]['sport'] == "NBA"){
          $athlete = $retData[$i]['athlete_name'];
          $stats = mysqli_query($db, "SELECT POINTS, REBOUNDS, ASSISTS from NBAPlayer where NAME = '{$athlete}'");
          $stats = $stats->fetch_assoc();
          $retData[$i]['stats'] = $stats;
        }
        else if($retData[$i]['sport'] == "EPL"){
          $athlete = $retData[$i]['athlete_name'];
          $stats = mysqli_query($db, "SELECT goals, assists from EPLPlayer where NAME = '{$athlete}'");
          $stats = $stats->fetch_assoc();
          $retData[$i]['stats'] = $stats;
        }
      }
    ?>
    <script type="text/javascript">
      console.log('<?php echo json_encode($retData);?>');
      var data = JSON.parse('<?php echo json_encode($retData);?> ');
      var urls = JSON.parse('<?php echo json_encode($retData2);?>');
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
        <div>
      <div class="jumbotron alert-success">
              <h1>Welcome to your team <?php echo $_COOKIE["user"];?>! </h1>
      </div>  
      <table class="table table-hover" id="mainTable">
              <thead>
                  <tr>
                    <th>Sport</th>
                    <th>Player Name</th>
                    <th>Player Team</th>
                    <th>Player Position</th>
                    <th>Points <button class="list-unstyled" style="color:blue;" href = "#myModal3" data-toggle = "modal" name="singlebutton" class="btn btn-primary center-block">&nbsp;?</button></th>
                    <th>Stats</th>
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
              <label>Old Password&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
              <input type="password" name="oldPassword" required>
              <br/>
              <br/>
              <label>New Password&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
              <input type="password" name="newPassword" required>
              <br/><br/>
              <label>Confirm Password&nbsp;&nbsp;</label>
              <input type="password" name="confirm" required>
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

      <div class = "modal" id = "myModal3">
        <div class = "modal-dialog">
          <div class = "modal-content">
            <div class = "modal-header">
              <h2>How are points calculated?<h2>
            </div>
            <div class = "modal-body">
            <p>We calculate the total points of each athlete based on a normalization formula. We use the totals of each of the statistics that we track (i.e. touchdowns, yards, rebounds, assists, etc.) to make sure that each sport is represented equally. We constantly update this normalization weekly using the minimum statistic, and accordingly allocating point values to the other statistics.</p>
            </div>
            <div class = "modal-footer">
              <button type = "button" class = "btn btn-primary" data-dismiss = "modal">Close</button>
            </div>
          </div>
        </div>
      </div>

  </body>
</html>