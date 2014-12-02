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
      $username = $_GET["players_for"];
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
      <div class="jumbotron alert-success">
              <h1><?php echo $username;?>'s team</h1>
              <a href="rankings.php">Go Back</a>
      </div>  
      <table class="table table-hover" id="mainTable">
              <thead>
                  <tr>
                    <th>Sport</th>
                    <th>Player Name</th>
                    <th>Player Team</th>
                    <th>Player Position</th>
                    <th>Points</th>
                    <th>Stats</th>
                  </tr>
              </thead>
              <tbody> 
              </tbody>
          </table>
      </div>
  </body>
</html>