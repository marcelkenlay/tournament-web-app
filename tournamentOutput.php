<?php
  function outputTournament($tournamentFileNo, $edit){
    require 'tableFunctions.php';
    require 'fileHandling.php';

    $tournamentFileName = "../data/tournaments/tournament" . $tournamentFileNo . ".txt";
    $tournamentFileText = readFileText($tournamentFileName);
    $records = explode("\n", $tournamentFileText);
    $newRecords = [];

    if (!$edit){
      echo "<a href=\"completeTournament.php?tournNum=$tournamentFileNo\">Click here to edit results</a>";
    }

    $playerFileText = readFileText("../data/playerNames.txt");
    $playerNames = explode(",",$playerFileText);

    $playerStats = array();
    $playerNumbers = explode(",", $records[0]);

    for ($i=0; $i < count($playerNumbers); $i++) {
      $stats = array("Points"=>0, "Wins"=>0, "Draws"=>0, "Losses"=>0, "GoalsFor"=>0, "GoalsAgainst"=>0);
      $playerStats[($playerNumbers[$i])] = $stats;
    }

    echo "<form method=\"POST\">";

    for ($i=1; $i < count($records); $i++) {
      if (strlen($records[$i]) > 0){
        $fields = explode(",", $records[$i]);
        if ($edit && isset($_POST["homeScore$i"])){
          $fields[1] = $_POST["homeScore$i"];
        }
        if ($edit && isset($_POST["homeScore$i"])){
          $fields[2] = $_POST["awayScore$i"];
        }

        if (strlen($fields[1]) > 0 && strlen($fields[2]) > 0){
          $playerStats[($fields[0])]['GoalsFor'] += $fields[1];
          $playerStats[($fields[3])]['GoalsAgainst'] += $fields[1];
          $playerStats[($fields[0])]['GoalsAgainst'] += $fields[2];
          $playerStats[($fields[3])]['GoalsFor'] += $fields[2];
          if ($fields[1] > $fields[2]) {
            $playerStats[($fields[0])]['Wins'] += 1;
            $playerStats[($fields[3])]['Losses'] += 1;
          } elseif ($fields[1] < $fields[2]) {
            $playerStats[($fields[0])]['Losses'] += 1;
            $playerStats[($fields[3])]['Wins'] += 1;
          } else {
            $playerStats[(string) ($fields[0])]['Draws'] += 1;
            $playerStats[(string) ($fields[3])]['Draws'] += 1;
          }
        }
          $gamesPerRound = (int) (count($playerNumbers) / 2);
        if((($i-1) % $gamesPerRound) == 0){
          $round = (($i - 1) / $gamesPerRound) + 1;
          echo "<h2>Round $round</h2>";
        }
        echo "<div class=\"left\">" . $playerNames[$fields[0]] . "</div>";
        echo "<input " . ($edit ? "" : "readonly") . " class=\"goalInputs\" type=\"number\" oninput=\"updateBackground(this, " . $fields[1] . ")\" name=\"homeScore$i\" value=\"" . $fields[1] . "\"/>";
        echo "<input " . ($edit ? "" : "readonly") . " class=\"goalInputs\" type=\"number\" oninput=\"updateBackground(this, " . $fields[2] . ")\" name=\"awayScore$i\" value=\"" . $fields[2] . "\"/>";
        echo "<div class=\"right\">" . $playerNames[$fields[3]] . "</div>";
        echo "<br>";
        array_push($newRecords, $fields);
      }
    }
    if ($edit){
      $tournamentFile = fopen($tournamentFileName, "w") or die("Unable to open file!");
      fwrite($tournamentFile,$records[0] . "\n");
      for ($i=0; $i < count($newRecords); $i++) {
        fwrite($tournamentFile, $newRecords[$i][0] . ","  . $newRecords[$i][1] . ","  . $newRecords[$i][2] . "," . $newRecords[$i][3]  . "\n");
      }
      fclose($tournamentFile);
    }

    $playerNumbers = quick_sort($playerNumbers, $playerStats, $standardSorting);

    if ($edit){
      echo "<input id=\"submitBtn\" value=\"SAVE RESULTS\" type=\"submit\"/>";
    }

    echo "<br>";
    echo "<h1>Current Table</h1>";


    echo "<table class=\"tourneyTable\" border=\"1\" cellpadding=\"2\">\n";
    echo "<tr><th class=\"tourneyTable\">Pos</td>
              <th>Name</th>
              <th>GP</th>
              <th>Pts</th>
              <th>W</th>
              <th>D</th>
              <th>L</th>
              <th>GF</th>
              <th>GA</th>
              <th>GD</th>
              </tr>";
    for ($i=0; $i < count($playerNumbers); $i++) {
      $gamesPlayed = $playerStats[$playerNumbers[$i]]['Wins'] + $playerStats[$playerNumbers[$i]]['Draws']
                    + $playerStats[$playerNumbers[$i]]['Losses'];
      $points =  3 * $playerStats[$playerNumbers[$i]]['Wins'] + $playerStats[$playerNumbers[$i]]['Draws'];
      $goalDifference = $playerStats[$playerNumbers[$i]]['GoalsFor'] - $playerStats[$playerNumbers[$i]]['GoalsAgainst'];

      echo "<tr><td>" . ($i + 1) . "</td>";
      echo "<td>" . $playerNames[$playerNumbers[$i]] . "</td>";
      echo "<td>" . $gamesPlayed . "</td>";
      echo "<td>" . $points . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$i]]['Wins'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$i]]['Draws'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$i]]['Losses'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$i]]['GoalsFor'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$i]]['GoalsAgainst'] . "</td>";
      echo "<td>" . $goalDifference . "</td><tr>";
    }

    echo "</table><br>";

    echo "</form>";
  }
 ?>
