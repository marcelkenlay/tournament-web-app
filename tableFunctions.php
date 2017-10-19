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

  function echo_inner_table($playerNumbers, $playerStats, $playerNames){
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

  }

  function processTournamentData($playerNumbers, $tournamentRecords){

    $playerStats = array();
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
    return $playerStats;
  }
?>
