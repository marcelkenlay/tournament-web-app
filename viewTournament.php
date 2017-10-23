<html>

<head>
  <link rel="stylesheet" href="stylesheet2.css"/>
    <script type="text/javascript" src="completeTournament.js"></script>
  <title>Previous Tournamentss</title>
</head>

<body onload="load()">
  <?php


    require 'header.php';
    echoHeader(-1);

    session_start();

    $tournamentFileNo = $_GET['tournNum'];
    require 'tournamentOutput.php';
    outputTournament($tournamentFileNo, false);

    // $tournamentFileNo = $_GET['tournNum'];
    // $tournamentFileText = readFileText($tournamentFileName);
    // fclose($tournamentFile);
    //
    // echo "<a href=\"completeTournament.php?tournNum=$tournamentFileNo\">Click here to edit results</a>";
    //
    //
    // $playerFile = fopen("../data/playerNames.txt", "r") or die("Unable to open file!");
    // $playerFileText = fread($playerFile,filesize("../data/playerNames.txt"));
    // $playerFileText = preg_replace('/\s+/', '', $playerFileText);
    // fclose($playerFile);
    // $playerNames = explode(",",$playerFileText);
    //
    // $records = explode("\n", $tournamentFileText);
    // $newRecords = [];
    //
    // $playerStats = array();
    // $playerNumbers = explode(",", $records[0]);
    // for ($i=0; $i < count($playerNumbers); $i++) {
    //   $stats = array("Points"=>0, "Wins"=>0, "Draws"=>0, "Losses"=>0, "GoalsFor"=>0, "GoalsAgainst"=>0);
    //   $playerStats[($playerNumbers[$i])] = $stats;
    // }
    //
    // for ($i=1; $i < count($records); $i++) {
    //   if (strlen($records[$i]) > 0){
    //     $fields = explode(",", $records[$i]);
    //     if (strlen($fields[1]) > 0 && strlen($fields[2]) > 0){
    //       $playerStats[($fields[0])]['GoalsFor'] += $fields[1];
    //       $playerStats[($fields[3])]['GoalsAgainst'] += $fields[1];
    //       $playerStats[($fields[0])]['GoalsAgainst'] += $fields[2];
    //       $playerStats[($fields[3])]['GoalsFor'] += $fields[2];
    //       if ($fields[1] > $fields[2]) {
    //         $playerStats[($fields[0])]['Wins'] += 1;
    //         $playerStats[($fields[3])]['Losses'] += 1;
    //       } elseif ($fields[1] < $fields[2]) {
    //         $playerStats[($fields[0])]['Losses'] += 1;
    //         $playerStats[($fields[3])]['Wins'] += 1;
    //       } else {
    //         $playerStats[(string) ($fields[0])]['Draws'] += 1;
    //         $playerStats[(string) ($fields[3])]['Draws'] += 1;
    //       }
    //     }
    //       $gamesPerRound = (int) (count($playerNumbers) / 2);
    //     if((($i-1) % $gamesPerRound) == 0){
    //       $round = (($i - 1) / $gamesPerRound) + 1;
    //       echo "<h2>Round $round</h2>";
    //     }
    //     echo "<div class=\"left\">" . $playerNames[$fields[0]] . "</div>";
    //     echo "<input readonly class=\"goalInputs\" type=\"number\" oninput=\"updateBackground(this, " . $fields[1] . ")\" name=\"homeScore$i\" value=\"" . $fields[1] . "\"/>";
    //     echo "<input readonly class=\"goalInputs\" type=\"number\" oninput=\"updateBackground(this, " . $fields[2] . ")\" name=\"awayScore$i\" value=\"" . $fields[2] . "\"/>";
    //     echo "<div class=\"right\">" . $playerNames[$fields[3]] . "</div>";
    //     echo "<br>";
    //     array_push($newRecords, $fields);
    //   }
    // }
    //
    // $tournamentFile = fopen($tournamentFileName, "w") or die("Unable to open file!");
    // fwrite($tournamentFile,$records[0] . "\n");
    // for ($i=0; $i < count($newRecords); $i++) {
    //   fwrite($tournamentFile, $newRecords[$i][0] . ","  . $newRecords[$i][1] . ","  . $newRecords[$i][2] . "," . $newRecords[$i][3]  . "\n");
    // }
    // fclose($tournamentFile);
    //
    //
    // function quick_sort($array, $playerStats){
    //   $length = count($array);
    // 	if($length <= 1){
    // 	  return $array;
    // 	} else {
    // 	  $pivot = $array[0];
    //     $pivotPoints = 3 * $playerStats[$array[0]]['Wins']
    //            + $playerStats[$array[0]]['Draws'];
    //     $pivotGoalDiff = $playerStats[$array[0]]['GoalsFor'] - $playerStats[$array[0]]['GoalsAgainst'];
    // 	  $left = $right = array();
    // 	  for($i = 1; $i < count($array); $i++){
    //       $points = 3 * $playerStats[$array[$i]]['Wins'] + $playerStats[$array[$i]]['Draws'];
    //       $goalDiff = $playerStats[$array[$i]]['GoalsFor'] - $playerStats[$array[$i]]['GoalsAgainst'];
    // 	    if($points > $pivotPoints
    //           || ($points == $pivotPoints && $goalDiff > $pivotGoalDiff)){
    // 		     $left[] = $array[$i];
    // 			} else {
    // 				 $right[] = $array[$i];
    // 			}
    // 		 }
    //      return array_merge(quick_sort($left, $playerStats), array($pivot), quick_sort($right, $playerStats));
    // 	}
    // }
    //
    // $playerNumbers = quick_sort($playerNumbers, $playerStats);
    //
    // echo "<br>";
    // echo "<h1>Current Table</h1>";
    //
    // echo "<table class=\"tourneyTable\" border=\"1\" cellpadding=\"2\">\n";
    // echo "<tr><th class=\"tourneyTable\">Pos</td>
    //           <th>Name</th>
    //           <th>GP</th>
    //           <th>Pts</th>
    //           <th>W</th>
    //           <th>D</th>
    //           <th>L</th>
    //           <th>GF</th>
    //           <th>GA</th>
    //           <th>GD</th>
    //           </tr>";
    //
    // for ($i=0; $i < count($playerNumbers); $i++) {
    //   $gamesPlayed = $playerStats[$playerNumbers[$i]]['Wins'] + $playerStats[$playerNumbers[$i]]['Draws']
    //                 + $playerStats[$playerNumbers[$i]]['Losses'];
    //   $points =  3 * $playerStats[$playerNumbers[$i]]['Wins'] + $playerStats[$playerNumbers[$i]]['Draws'];
    //   $goalDifference = $playerStats[$playerNumbers[$i]]['GoalsFor'] - $playerStats[$playerNumbers[$i]]['GoalsAgainst'];
    //   echo "<tr><td>" . ($i + 1) . "</td>";
    //   echo "<td>" . $playerNames[$playerNumbers[$i]] . "</td>";
    //   echo "<td>" . $gamesPlayed . "</td>";
    //   echo "<td>" . $points . "</td>";
    //   echo "<td>" . $playerStats[$playerNumbers[$i]]['Wins'] . "</td>";
    //   echo "<td>" . $playerStats[$playerNumbers[$i]]['Draws'] . "</td>";
    //   echo "<td>" . $playerStats[$playerNumbers[$i]]['Losses'] . "</td>";
    //   echo "<td>" . $playerStats[$playerNumbers[$i]]['GoalsFor'] . "</td>";
    //   echo "<td>" . $playerStats[$playerNumbers[$i]]['GoalsAgainst'] . "</td>";
    //   echo "<td>" . $goalDifference . "</td><tr>";
    // }
    //
    // echo "</table><br>";
    //
   ?>

</body>

</html>
