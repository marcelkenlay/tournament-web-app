<html>

<head>
  <link rel="stylesheet" href="stylesheet2.css"/>
    <script type="text/javascript" src="pastTournament.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Previous Tournaments</title>
</head>

<body onload="load()">

  <!-- </head>
  <body onload="load()">
    <div class="wrapper">
      <div class="navigation fixed">
        <h4>Tournament Tracker</h4>
        <ul class="nav">
          <li><a href="index.php">Past Tournaments</a></li>
          <li class="active"><a href="overallLeaderboard.php">Leaderboard</a></li>
          <li><a href="playerRecord.php">Player Record</a></li>
          <li><a href="addTournament.php">Add a Tournament</a></li>
        </ul>
      </div>
    </div> -->


      <div class="wrapper">
        <div class="navigation">
          <ul class="nav">
            <li class="active"><a href="index.php">Past Tournaments</a></li>
            <li><a href="overallLeaderboard.php">Leaderboard</a></li>
            <li><a href="playerRecord.php">Player Record</a></li>
            <li><a href="addTournament.php">Add a Tournament</a></li>
          </ul>
        </div>
      </div>

  <div id="mydata">
    <h1>PREVIOUS TOURNAMENTS</h1>
    <p>Click a tournament to view the table.</p>
      <table id="myTableData" class="pastTournaments expands" border="1" cellpadding="2">
        <tr>
          <th>Tournament Name</th>
          <th>1st Place</th>
        </tr>
        <?php

        function quick_sort($array, $playerStats){
          $length = count($array);
          if($length <= 1){
            return $array;
          } else {
            $pivot = $array[0];
            $pivotPoints = 3 * $playerStats[$array[0]]['Wins']
                   + $playerStats[$array[0]]['Draws'];
            $pivotGoalDiff = $playerStats[$array[0]]['GoalsFor'] - $playerStats[$array[0]]['GoalsAgainst'];
            $left = $right = array();
            for($k = 1; $k < count($array); $k++){
              $points = 3 * $playerStats[$array[$k]]['Wins'] + $playerStats[$array[$k]]['Draws'];
              $goalDiff = $playerStats[$array[$k]]['GoalsFor'] - $playerStats[$array[$k]]['GoalsAgainst'];
              if($points > $pivotPoints
                  || ($points == $pivotPoints && $goalDiff > $pivotGoalDiff)){
                 $left[] = $array[$k];
              } else {
                 $right[] = $array[$k];
              }
             }
             return array_merge(quick_sort($left, $playerStats), array($pivot), quick_sort($right, $playerStats));
          }
        }


        $playerFile = fopen("../data/playerNames.txt", "r") or die("Unable to open file!");
        $playerFileText = fread($playerFile,filesize("../data/playerNames.txt"));
        $playerFileText = preg_replace('/\s+/', '', $playerFileText);
        fclose($playerFile);
        $playerNames = explode(",",$playerFileText);


        $myfile = fopen("../data/pastTournamentNames.txt", "r") or die("Unable to open file!");
        $fileText = fread($myfile,filesize("../data/pastTournamentNames.txt"));
        fclose($myfile);
        $records = explode("\n",$fileText);
        $rowNum = 1;


        for ($i=count($records) -1; $i >= 0; $i--) {
          if(strlen($records[$i]) > 0){
            $fields = explode("|",$records[$i]);



            $tournamentFileName = "../data/tournaments/tournament". $fields[1] . ".txt";
            $tournamentFileName = preg_replace('/\s+/', '', $tournamentFileName);

            $tournamentFile = fopen($tournamentFileName, "r") or die("Unable to open file!");
            $tournamentFileText = fread($tournamentFile,filesize($tournamentFileName));
            fclose($tournamentFile);

            $tableID = "tournamentTable" . $i;


            $tournamentRecords = explode("\n", $tournamentFileText);

            $playerStats = array();
            $playerNumbers = explode(",", $tournamentRecords[0]);
            for ($j=0; $j < count($playerNumbers); $j++) {
              $stats = array("Points"=>0, "Wins"=>0, "Draws"=>0, "Losses"=>0, "GoalsFor"=>0, "GoalsAgainst"=>0);
              $playerStats[($playerNumbers[$j])] = $stats;
            }

            for ($j=1; $j < count($tournamentRecords); $j++) {
              if (strlen($tournamentRecords[$j]) > 0){
                $matchFields = explode(",", $tournamentRecords[$j]);
                if (strlen($matchFields[1]) > 0 && strlen($matchFields[2]) > 0){
                  $playerStats[($matchFields[0])]['GoalsFor'] += $matchFields[1];
                  $playerStats[($matchFields[3])]['GoalsAgainst'] += $matchFields[1];
                  $playerStats[($matchFields[0])]['GoalsAgainst'] += $matchFields[2];
                  $playerStats[($matchFields[3])]['GoalsFor'] += $matchFields[2];
                  if ($matchFields[1] > $matchFields[2]) {
                    $playerStats[($matchFields[0])]['Wins'] += 1;
                    $playerStats[($matchFields[3])]['Losses'] += 1;
                  } elseif ($matchFields[1] < $matchFields[2]) {
                    $playerStats[($matchFields[0])]['Losses'] += 1;
                    $playerStats[($matchFields[3])]['Wins'] += 1;
                  } else {
                    $playerStats[($matchFields[0])]['Draws'] += 1;
                    $playerStats[($matchFields[3])]['Draws'] += 1;
                  }
                }
              }
            }


              $playerNumbers = quick_sort($playerNumbers, $playerStats);

              echo "<tr id=\"tr$rowNum\" onClick=\"Javacsript:expandTournament(this)\"><td>" . $fields[0] ."</td>";
              echo "<td>" . $playerNames[$playerNumbers[0]] . "</td>";

              $rowNum += 1;

              echo "<tr id=\"tr$rowNum\"  style=\"display: none\"><td class=\"tableHolder\" colspan=\"2\">\n";

              $rowNum += 1;

              echo "<table class=\"inner\" >\n";
              echo "<tr><th class=\"inner\">Pos</th>
                        <th class=\"inner\">Name</th>
                        <th class=\"inner\">GP</th>
                        <th class=\"inner\">Pts</th>
                        <th class=\"inner\">W</th>
                        <th class=\"inner\">D</th>
                        <th class=\"inner\">L</th>
                        <th class=\"inner\">GF</th>
                        <th class=\"inner\">GA</th>
                        <th class=\"inner\">GD</th>
                        </tr>\n";

              for ($j=0; $j < count($playerNumbers); $j++) {
                $gamesPlayed = $playerStats[$playerNumbers[$j]]['Wins'] + $playerStats[$playerNumbers[$j]]['Draws']
                              + $playerStats[$playerNumbers[$j]]['Losses'];
                $points =  3 * $playerStats[$playerNumbers[$j]]['Wins'] + $playerStats[$playerNumbers[$j]]['Draws'];
                $goalDifference = $playerStats[$playerNumbers[$j]]['GoalsFor'] - $playerStats[$playerNumbers[$j]]['GoalsAgainst'];
                echo "<tr><td>" . ($j + 1) . "</td>";
                echo "<td class=\"nameColumn\">" . $playerNames[$playerNumbers[$j]] . "</td>";
                echo "<td>" . $gamesPlayed . "</td>";
                echo "<td>" . $points . "</td>";
                echo "<td>" . $playerStats[$playerNumbers[$j]]['Wins'] . "</td>";
                echo "<td>" . $playerStats[$playerNumbers[$j]]['Draws'] . "</td>";
                echo "<td>" . $playerStats[$playerNumbers[$j]]['Losses'] . "</td>";
                echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsFor'] . "</td>";
                echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsAgainst'] . "</td>";
                echo "<td>" . $goalDifference . "</td><tr>";
              }

              echo "</table>";
              echo "<a href=\"viewTournament.php?tournNum=" . $fields[1] . "\">Click here to view/enter results</a></td></tr>\n";

              echo"</tr>\n";
            }
          }

        ?>
      </table>
     &nbsp;<br/>
  </div>

</body>

</html>
