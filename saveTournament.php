<head>
  <link rel="stylesheet" href="stylesheet2.css"/>
  <script type="text/javascript" src="addTournament.js"></script>
<title>Add A Tournament</title>
</head>
  <?php

    require 'header.php';
    echoHeader(-1);

    $playerFile = fopen("../data/playerNames.txt", "r") or die("Unable to open file!");
    $playerFileText = fread($playerFile,filesize("../data/playerNames.txt"));
    $playerFileText = preg_replace('/\s+/', '', $playerFileText);
    fclose($playerFile);
    $playerNames = explode(",",$playerFileText);

     $players = [];
     for ($i=1; !empty($_POST["playerSelect$i"]) ; $i++) {
       if ($_POST["playerSelect$i"] == "new") {
         array_push($players, count($playerNames));
         array_push($playerNames, $_POST["playerName$i"]);
       } else {
         $playerNo = 0;
         for (;$playerNames[$playerNo] != $_POST["playerSelect$i"]; $playerNo++) {}
         array_push($players,$playerNo);
       }
     }


     $playerFile = fopen("../data/playerNames.txt", "w") or die("Unable to open file!");
     for ($i=0; $i < count($playerNames) - 1; $i++) {
       fwrite($playerFile, $playerNames[$i] . ",");
     }
     fwrite($playerFile, $playerNames[count($playerNames) - 1]);
     fclose($playerFile);


    $myfile = fopen("../data/pastTournamentNames.txt", "r") or die("Unable to open file!");
    $fileText = fread($myfile,filesize("../data/pastTournamentNames.txt"));
    fclose($myfile);
    $records = explode("\n",$fileText);
    $name = $_POST["tournamentName"];
    $fileNo = 0;
    for ($i=0; $i < count($records); $i++) {
      if(strlen($records[$i]) > 0){
        $fields = explode("|",$records[$i]);
        $fileNo = (int) $fields[1];
        $fileNo += 1;
      }
    }
    $myfile = fopen("../data/pastTournamentNames.txt", "a") or die("Unable to open file!");
    if (count($records) > 0){
      fwrite($myfile, "\n");
    }
    fwrite($myfile, $name);
    fwrite($myfile, "|");
    fwrite($myfile, $fileNo);
    fclose($myfile);

    $tournamentFileName = "../data/tournaments/tournament" . $fileNo . ".txt";
    echo "$tournamentFileName";
    $tournamentFile = fopen($tournamentFileName, "w") or die("Unable to open file!");
    for ($i=0; $i < count($players) - 1; $i++) {
      fwrite($tournamentFile, $players[$i] . ",");
    }
    fwrite($tournamentFile, $players[count($players) - 1] . "\n");

    $noPlayers = count($players);
    $noTeams = $noPlayers;
    if ($noPlayers % 2 == 1){
      $noTeams += 1;
    }
    $gamesPerRound = $noPlayers / 2;
    $fixtures = [];
    $teams = [];
    for ($i = 0; $i < $noTeams; $i++) {
       array_push($teams, $i);
    }
    for ($i = 0; $i < $noTeams - 1; $i++) {
      for ($j = 0; $j < $noTeams / 2; $j++) {
        if ($teams[$j] < $noPlayers && $teams[$noTeams - 1 - $j] < $noPlayers ){
            array_push($fixtures,[$teams[$j], $teams[$noTeams - 1 - $j]] );
        }
      }
      $hold = $teams[$noTeams - 1];
      for ($j = $noTeams - 1; $j > 1; $j--) {
        $teams[$j] = $teams[$j -1];
      }
      $teams[1] = $hold;
    }

    session_start();
    $_SESSION['tournamentFileName'] = $tournamentFileName;

    for ($i=0; $i < count($fixtures); $i++) {
      $fixture = $players[$fixtures[$i][0]] . ",,," . $players[$fixtures[$i][1]] ."\n";
      fwrite($tournamentFile,$fixture);
    }

    fclose($tournamentFile);

    echo "<a href=\"completeTournament.php?tournNum=$fileNo\">Click here to enter results for this tournament</a>";

    $url="completeTournament.php?tournNum=$fileNo";
   echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$url.'">';
   ?>

</body>
