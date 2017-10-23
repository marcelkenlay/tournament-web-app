<head>
  <link rel="stylesheet" href="stylesheet2.css"/>
  <meta name="viewport" content="width=device-width" />
  <title>Leaderboard</title>
</head>
<body>
  <?php
    require 'header.php';
    echoHeader(1);
    require 'tableFunctions.php';
    require 'fileHandling.php';

    function echo_th_for($stat, $shorthand, $sortingStat){
      echo "<th><a class=\"";
      echo ($sortingStat == $stat ? "current" : "notCurrent");
      echo "\" href=\"overallLeaderboard.php?sortBy=$stat\">$shorthand";
      echo ($sortingStat == $stat ? "↓" : "");
      echo "</a></th>\n";

    }

    $playerFileText = readFileText("../data/playerNames.txt");
    $playerFileText = preg_replace('/\s+/', '', $playerFileText);
    $playerNames = explode(",",$playerFileText);

    $fileText = readFileText("../data/pastTournamentNames.txt");
    $pastTournamentRecords = explode("\n",$fileText);

    $playerNumbers = array();
    $playerStats = array();
    for ($i=0; $i < count($playerNames); $i++) {
      $stats = array("GamesPlayed"=>0, "Points"=>0,
                "Wins"=>0, "Draws"=>0, "Losses"=>0,
                 "GoalsFor"=>0, "GoalsAgainst"=>0, "WinRatio"=>0,
                  "GoalsForPerGame"=>0, "GoalsAgainstPerGame"=>0);
      $playerStats[$i] = $stats;
      $playerNumbers[$i] = $i;
    }

    for ($i=0; $i < count($pastTournamentRecords); $i++) {
      if(strlen($pastTournamentRecords[$i]) == 0){
        continue;
      }
      $tournamentNumber = (explode("|", $pastTournamentRecords[$i]))[1];

      $tournamentFileName = "../data/tournaments/tournament". $tournamentNumber . ".txt";
      $tournamentFileName = preg_replace('/\s+/', '', $tournamentFileName);
      $tournamentFileText = readFileText($tournamentFileName);

      $tournamentRecords = explode("\n", $tournamentFileText);

      $playerStats = processTournamentData($playerNumbers, $playerStats, $tournamentRecords);
    }

    for ($i=0; $i < count($playerNumbers); $i++) {
      $playerStats[$i]['GamesPlayed'] =
      $playerStats[$i]['Wins'] + $playerStats[$i]['Draws'] + $playerStats[$i]['Losses'];
      if ($playerStats[$i]['GamesPlayed'] == 0){
        continue;
      }
      $playerStats[$i]['Points'] = 3 *  $playerStats[$i]['Wins'] + $playerStats[$i]['Draws'];
      $playerStats[$i]['WinRatio'] =
      round (100 * $playerStats[$i]['Wins'] / $playerStats[$i]['GamesPlayed'], 0);
      $playerStats[$i]['GoalsForPerGame'] =
      round ($playerStats[$i]['GoalsFor'] / $playerStats[$i]['GamesPlayed'], 2);
      $playerStats[$i]['GoalsAgainstPerGame'] =
      round ($playerStats[$i]['GoalsAgainst'] / $playerStats[$i]['GamesPlayed'], 2);
      $playerStats[$i]['GoalDiff'] = $playerStats[$i]['GoalsFor'] - $playerStats[$i]['GoalsAgainst'];
    }

    $sortingStat = "WinRatio";
    if (!empty($_GET["sortBy"])){
      $sortingStat = $_GET["sortBy"];
    }

    $playerNumbers = quick_sort($playerNumbers, $playerStats, sort_stat_to_array($sortingStat));
    
    echo "<p>View in landscape for a better display.</p>";
    
    echo "<div id=\"scrollingWrapper\">";
    echo "<table id=\"overall\" border=\"1\" cellpadding=\"2\">\n";
    echo "<tr><th>Pos</th>
              <th>Name</th>";
    echo_th_for("GamesPlayed", "GP", $sortingStat);
    echo_th_for("Wins", "W", $sortingStat);
    echo_th_for("Draws", "D", $sortingStat);
    echo_th_for("Losses", "L", $sortingStat);
    echo_th_for("GoalsFor", "GF", $sortingStat);
    echo_th_for("GoalsAgainst", "GA", $sortingStat);
    echo_th_for("Points", "Pts", $sortingStat);
    echo_th_for("WinRatio", "W%", $sortingStat);
    echo_th_for("GoalsForPerGame", "GF/G", $sortingStat);
    echo_th_for("GoalsAgainstPerGame", "GA/G", $sortingStat);
    echo "</tr>\n";

    for ($j=0; $j < count($playerNumbers); $j++) {
      echo "<tr><td>" . ($j + 1) . "</td>";
      echo "<td class=\"catEndColumn\">" . $playerNames[$playerNumbers[$j]] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GamesPlayed'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['Wins'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['Draws'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['Losses'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsFor'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsAgainst'] . "</td>";
      echo "<td class=\"catEndColumn\">" . $playerStats[$playerNumbers[$j]]['Points'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['WinRatio'] . "%</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsForPerGame'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsAgainstPerGame'] . "</td>";
    }

    echo "</table></div>\n";


   ?>
</body>
