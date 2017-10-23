<html>

<head>
  <link rel="stylesheet" href="stylesheet2.css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="pastTournament.js"></script>
  <title>Player Record</title>
</head>

<body onload="load()">

  <?php
    require 'header.php';
    echoHeader(1);
  ?>

  <form>

  <?php
    require "tableFunctions.php";
    require "fileHandling.php";

    $playerFileText = readFileText("../data/playerNames.txt");
    $playerNames = explode(",",$playerFileText);

    $fileText = readFileText("../data/pastTournamentNames.txt");
    $pastTournamentRecords = explode("\n",$fileText);

    $playerToView = 0;

    if (!empty($_GET["playerNo"])){
      $playerToView = $_GET["playerNo"];
    }

    echo "<div id=\"wrapper\">";
    echo "<div class=\"playerSelect\">";
    echo "Player:";
    echo "<select id=\"playerRecordSelect\" onchange=\"this.form.submit()\" class=\"inputs\" name=\"playerNo\">";
    for ($j=0; $j < count($playerNames); $j++) {
      if($j == $playerToView){
        echo "<option selected=\"selected\" value=\"$j\"> $playerNames[$j]</option>";
      }
      echo "<option value=\"$j\">$playerNames[$j]</option>";
    }
    echo "</select>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    echo "<br>";

    $allPlayerNumbers = array();
    $playerStats = array();

    for ($i=0; $i < count($playerNames); $i++) {
      $stats = array("GamesPlayed"=>0, "Wins"=>0, "Draws"=>0, "Losses"=>0,
                 "GoalsFor"=>0, "GoalsAgainst"=>0, "LossRatio"=>0,
                  "GoalsForPerGame"=>0, "GoalsAgainstPerGame"=>0);
      $playerStats[$i] = $stats;
      $allPlayerNumbers[$i] = $i;
    }

    echo "<h2>Performance in Tournaments</h2>";

    echo "<table class=\"tourneyTable expands\" id=\"myTableData\">";
    echo "<tr>";
    echo "<th>Tournament</th>";
    echo "<th>Position</th>";
    echo "<th>Points</th>";
    echo "<th>% Above</th>";
    echo "</tr>";

    $rowNum = 1;

    for ($i=count($pastTournamentRecords)-1; $i >= 0; $i--) {

      if(strlen($pastTournamentRecords[$i]) == 0){
        continue;
      }

      $tournamentNameFields = explode("|", $pastTournamentRecords[$i]);

      $tournamentNumber = $tournamentNameFields[1];

      $tournamentFileName = "../data/tournaments/tournament". $tournamentNumber . ".txt";
      $tournamentFileName = preg_replace('/\s+/', '', $tournamentFileName);
      $tournamentFileText = readFileText($tournamentFileName);
      $tournamentRecords = explode("\n", $tournamentFileText);

      $playerNumbers = explode(",",$tournamentRecords[0]);
      $playerStatsCurrent = array();

      $ptvInTourney = FALSE;
      for ($k=0; $k < count($playerNumbers); $k++) {
        $stats = array("GamesPlayed"=>0, "Wins"=>0, "Draws"=>0, "Losses"=>0,
                   "GoalsFor"=>0, "GoalsAgainst"=>0, "LossRatio"=>0,
                    "GoalsForPerGame"=>0, "GoalsAgainstPerGame"=>0);
        $playerStatsCurrent[$playerNumbers[$k]] = $stats;
      }

      for ($j=1; $j < count($tournamentRecords); $j++) {
        if (strlen($tournamentRecords[$j]) == 0){
          continue;
        }
        $matchFields = explode(",", $tournamentRecords[$j]);
        if (strlen($matchFields[1]) > 0 && strlen($matchFields[2]) > 0){
          if ($matchFields[0] == $playerToView || $matchFields[3] == $playerToView){
            $ptvInTourney = TRUE;
            $playerStats[($matchFields[0])]['GamesPlayed'] += 1;
            $playerStats[($matchFields[3])]['GamesPlayed'] += 1;
            $playerStats[($matchFields[0])]['GoalsFor'] += $matchFields[1];
            $playerStats[($matchFields[3])]['GoalsAgainst'] += $matchFields[1];
            $playerStats[($matchFields[0])]['GoalsAgainst'] += $matchFields[2];
            $playerStats[($matchFields[3])]['GoalsFor'] += $matchFields[2];
          }
          $playerStatsCurrent[($matchFields[0])]['GamesPlayed'] += 1;
          $playerStatsCurrent[($matchFields[3])]['GamesPlayed'] += 1;
          $playerStatsCurrent[($matchFields[0])]['GoalsFor'] += $matchFields[1];
          $playerStatsCurrent[($matchFields[3])]['GoalsAgainst'] += $matchFields[1];
          $playerStatsCurrent[($matchFields[0])]['GoalsAgainst'] += $matchFields[2];
          $playerStatsCurrent[($matchFields[3])]['GoalsFor'] += $matchFields[2];
          if ($matchFields[1] > $matchFields[2]) {
            if ($matchFields[0] == $playerToView || $matchFields[3] == $playerToView){
              $playerStats[($matchFields[0])]['Wins'] += 1;
              $playerStats[($matchFields[3])]['Losses'] += 1;
            }
            $playerStatsCurrent[($matchFields[0])]['Wins'] += 1;
            $playerStatsCurrent[($matchFields[3])]['Losses'] += 1;
          } elseif ($matchFields[1] < $matchFields[2]) {
            if ($matchFields[0] == $playerToView || $matchFields[3] == $playerToView){
              $playerStats[($matchFields[0])]['Losses'] += 1;
              $playerStats[($matchFields[3])]['Wins'] += 1;
            }
            $playerStatsCurrent[($matchFields[0])]['Losses'] += 1;
            $playerStatsCurrent[($matchFields[3])]['Wins'] += 1;
          } else {
            if ($matchFields[0] == $playerToView || $matchFields[3] == $playerToView){
              $playerStats[($matchFields[0])]['Draws'] += 1;
              $playerStats[($matchFields[3])]['Draws'] += 1;
            }
            $playerStatsCurrent[(string) ($matchFields[0])]['Draws'] += 1;
            $playerStatsCurrent[(string) ($matchFields[3])]['Draws'] += 1;
          }
        }
      }

      if (!$ptvInTourney){
        continue;
      }

      $playerNumbers = quick_sort($playerNumbers, $playerStatsCurrent, $standardSorting);

      $playerPosition = 0;
      for ($k=0; $k < count($playerNumbers); $k++) {
        if ($playerNumbers[$k] == $playerToView){
          $playerPosition = $k + 1;
          break;
        }
      }

      $positionString = $playerPosition . "th";
      if ($playerPosition % 10 == 1 && $playerPosition != 11){
        $positionString = $playerPosition . "st";
      } elseif ($playerPosition % 10 == 2 && $playerPosition != 12) {
        $positionString = $playerPosition . "nd";
      } elseif ($playerPosition % 10 == 3 && $playerPosition != 13) {
        $positionString = $playerPosition . "rd";
      }

      echo "<tr id=\"tr$rowNum\" onClick=\"Javacsript:expandTournament(this)\">";
      echo "<td>" . $tournamentNameFields[0] . "</td>";
      if ($playerPosition == 1){
        echo "<td style=\"background-color:#CFB53B\">";
      } elseif ($playerPosition == 2) {
        echo "<td style=\"background-color:#C0C0C0\">";
      } elseif ($playerPosition == 3) {
        echo "<td style=\"background-color:#CD7F32\">";
      } else {
        echo "<td>";
      }
      echo $positionString . "</td>";
      $points = $playerStatsCurrent[$playerToView]['Wins'] * 3
                  + $playerStatsCurrent[$playerToView]['Draws'];
      $totalPoints = $playerStatsCurrent[$playerToView]['GamesPlayed'] * 3;
      echo "<td>$points/$totalPoints</td>";
      $percentageAbove = round(100 * (count($playerNumbers) - $playerPosition) / (count($playerNumbers) - 1),1);
      echo "<td>$percentageAbove%</td>";
      echo "</tr>";

      $rowNum += 1;

      echo "<tr id=\"tr$rowNum\"  style=\"display: none\"><td class=\"tableHolder\" colspan=\"4\">";
      //Calls function which outputs the table
      echo_inner_table($playerNumbers, $playerStatsCurrent, $playerNames);
      //Link takes user to page which will display the results and the tournament
      echo "<a href=\"viewTournament.php?tournNum=" . $tournamentNameFields[1] . "\">Click here to view/enter results</a></td></tr>\n";
      echo"</tr>\n";

      $rowNum += 1;

    }
    echo "</table>";

    for ($i=0; $i < count($allPlayerNumbers); $i++) {
      if ($playerStats[$i]['GamesPlayed'] == 0){
        continue;
      }
      $playerStats[$i]['LossRatio'] = round (100 * $playerStats[$i]['Losses']
                                      / $playerStats[$i]['GamesPlayed']);
      $playerStats[$i]['GoalsForPerGame'] =
      round ($playerStats[$i]['GoalsFor'] / $playerStats[$i]['GamesPlayed'], 2);
      $playerStats[$i]['GoalsAgainstPerGame'] =
      round ($playerStats[$i]['GoalsAgainst'] / $playerStats[$i]['GamesPlayed'], 2);
    }
    echo "<h2> $playerNames[$playerToView] record vs opponents</h2>";
    echo "<table class=\"playerVsPlayer\"><tr>";
    echo "<th class=\"catEndColumn\">Opponent</th>";
    echo "<th class=\"results\">GP</th>";
    echo "<th class=\"results\">W</th>";
    echo "<th class=\"results\">D</th>";
    echo "<th class=\"results\">L</th>";
    echo "<th class=\"results\">W%</th>";
    echo "<th class=\"goals\">GF</th>";
    echo "<th class=\"goals\">GA</th>";
    echo "<th class=\"goals\">GF/G</th>";
    echo "<th class=\"goals\">GA/G</th>";
    echo "</tr>";

    for ($j=0; $playerToView >= 0 && $j < count($allPlayerNumbers); $j++) {
      if ($allPlayerNumbers[$j] == $playerToView ||$playerStats[$allPlayerNumbers[$j]]['GamesPlayed'] == 0) {
          continue;
      }
      echo "<tr><td class=\"catEndColumn\">vs " . $playerNames[$allPlayerNumbers[$j]] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['GamesPlayed'] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['Losses'] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['Draws'] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['Wins'] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['LossRatio'] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['GoalsAgainst'] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['GoalsFor'] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['GoalsAgainstPerGame'] . "</td>";
      echo "<td>" . $playerStats[$allPlayerNumbers[$j]]['GoalsForPerGame'] . "</td>";
      echo "</tr>";
    }
    echo "</table>"

  ?>




</body>

</html>
