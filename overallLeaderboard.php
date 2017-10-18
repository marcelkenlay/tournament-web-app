<head>
  <link rel="stylesheet" href="stylesheet2.css"/>
  <meta name="viewport" content="width=device-width" />
  <title>Leaderboard</title>
</head>
<body>
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
            <li><a href="index.php">Past Tournaments</a></li>
            <li class="active"><a href="overallLeaderboard.php">Leaderboard</a></li>
            <li><a href="playerRecord.php">Player Record</a></li>
            <li><a href="addTournament.php">Add a Tournament</a></li>
          </ul>
        </div>
      </div>
  <?php
    function quick_sort($array, $playerStats, $sortingStat, $ascending){
      $length = count($array);
      if($length <= 1){
        return $array;
      } else {
        $pivot = $array[0];
        $left = $right = array();
        for($k = 1; $k < count($array); $k++){
          if((!$ascending && $playerStats[$pivot][$sortingStat] < $playerStats[$array[$k]][$sortingStat])
              || ($ascending && $playerStats[$pivot][$sortingStat] > $playerStats[$array[$k]][$sortingStat])){
             $left[] = $array[$k];
          } else {
             $right[] = $array[$k];
          }
         }
         return array_merge(quick_sort($left, $playerStats, $sortingStat, $ascending),
                          array($pivot), quick_sort($right, $playerStats, $sortingStat, $ascending));
      }
    }

    $playerFile = fopen("../data/playerNames.txt", "r") or die("Unable to open file!");
    $playerFileText = fread($playerFile,filesize("../data/playerNames.txt"));
    $playerFileText = preg_replace('/\s+/', '', $playerFileText);
    fclose($playerFile);
    $playerNames = explode(",",$playerFileText);

    $pastTournamentFile = fopen("../data/pastTournamentNames.txt", "r") or die("Unable to open file!");
    $fileText = fread($pastTournamentFile,filesize("../data/pastTournamentNames.txt"));
    fclose($pastTournamentFile);
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

      $tournamentFile = fopen($tournamentFileName, "r") or die("Unable to open file!");
      $tournamentFileText = fread($tournamentFile,filesize($tournamentFileName));
      fclose($tournamentFile);

      $tournamentRecords = explode("\n", $tournamentFileText);

      for ($j=1; $j < count($tournamentRecords); $j++) {
        if (strlen($tournamentRecords[$j]) == 0){
          continue;
        }
        $matchFields = explode(",", $tournamentRecords[$j]);
        if (strlen($matchFields[1]) > 0 && strlen($matchFields[2]) > 0){
          $playerStats[($matchFields[0])]['GamesPlayed'] += 1;
            $playerStats[($matchFields[3])]['GamesPlayed'] += 1;
          $playerStats[($matchFields[0])]['GoalsFor'] += $matchFields[1];
          $playerStats[($matchFields[3])]['GoalsAgainst'] += $matchFields[1];
          $playerStats[($matchFields[0])]['GoalsAgainst'] += $matchFields[2];
          $playerStats[($matchFields[3])]['GoalsFor'] += $matchFields[2];
          if ($matchFields[1] > $matchFields[2]) {
            $playerStats[($matchFields[0])]['Wins'] += 1;
            $playerStats[($matchFields[0])]['Points'] += 3;
            $playerStats[($matchFields[3])]['Losses'] += 1;
          } elseif ($matchFields[1] < $matchFields[2]) {
            $playerStats[($matchFields[0])]['Losses'] += 1;
            $playerStats[($matchFields[3])]['Points'] += 3;
            $playerStats[($matchFields[3])]['Wins'] += 1;
          } else {
            $playerStats[($matchFields[0])]['Draws'] += 1;
            $playerStats[($matchFields[0])]['Points'] += 1;
            $playerStats[($matchFields[3])]['Draws'] += 1;
            $playerStats[($matchFields[3])]['Points'] += 1;
          }
        }
      }
    }

    for ($i=0; $i < count($playerNumbers); $i++) {
      if ($playerStats[$i]['GamesPlayed'] == 0){
        continue;
      }
      $playerStats[$i]['WinRatio'] =
      round (100 * $playerStats[$i]['Wins'] / $playerStats[$i]['GamesPlayed'], 0);
      $playerStats[$i]['GoalsForPerGame'] =
      round ($playerStats[$i]['GoalsFor'] / $playerStats[$i]['GamesPlayed'], 2);
      $playerStats[$i]['GoalsAgainstPerGame'] =
      round ($playerStats[$i]['GoalsAgainst'] / $playerStats[$i]['GamesPlayed'], 2);
    }

    $sortingStat = "WinRatio";
    if (!empty($_GET["sortBy"])){
      $sortingStat = $_GET["sortBy"];
    }

    $playerNumbers = quick_sort($playerNumbers, $playerStats, $sortingStat, FALSE);
    
    echo "<p>View in landscape for a better display.</p>";
    
    echo "<div id=\"scrollingWrapper\">";
    echo "<table id=\"overall\" border=\"1\" cellpadding=\"2\">\n";
    echo "<tr><th>Pos</th>
              <th>Name</th>";
    if ($sortingStat == "GamesPlayed"){
      echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=GamesPlayed\">GP↓</a></th>";
    } else {
      echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=GamesPlayed\">GP</a></th>";
    }
    if ($sortingStat == "Points"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=Points\">Pts↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=Points\">Pts</a></th>";
    }
    if ($sortingStat == "Wins"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=Wins\">W↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=Wins\">W</a></th>";
    }
    if ($sortingStat == "Draws"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=Draws\">D↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=Draws\">D</a></th>";
    }
    if ($sortingStat == "Losses"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=Losses\">L↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=Losses\">L</a></th>";
    }
    if ($sortingStat == "GoalsFor"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=GoalsFor\">GF↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=GoalsFor\">GF</a></th>";
    }
    if ($sortingStat == "GoalsAgainst"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=GoalsAgainst\">GA↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=GoalsAgainst\">GA</a></th>";
    }
    if ($sortingStat == "WinRatio"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=WinRatio\">W%↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=WinRatio\">W%</a></th>";
    }
    if ($sortingStat == "GoalsForPerGame"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=GoalsForPerGame\">GF/G↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=GoalsForPerGame\">GF/G</a></th>";
    }
    if ($sortingStat == "GoalsAgainstPerGame"){
    echo "<th><a class=\"current\" href=\"overallLeaderboard.php?sortBy=GoalsAgainstPerGame\">GA/G↓</a></th>";
    } else {
    echo "<th><a class=\"notCurrent\" href=\"overallLeaderboard.php?sortBy=GoalsAgainstPerGame\">GA/G</a></th>";
    }

    echo "</tr>\n";

    for ($j=0; $j < count($playerNumbers); $j++) {
      echo "<tr><td>" . ($j + 1) . "</td>";
      echo "<td class=\"nameColumn\">" . $playerNames[$playerNumbers[$j]] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GamesPlayed'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['Points'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['Wins'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['Draws'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['Losses'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsFor'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsAgainst'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['WinRatio'] . "%</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsForPerGame'] . "</td>";
      echo "<td>" . $playerStats[$playerNumbers[$j]]['GoalsAgainstPerGame'] . "</td>";
    }

    echo "</table></div>\n";


   ?>
</body>
