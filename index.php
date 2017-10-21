<html>

<head>
  <link rel="stylesheet" href="stylesheet2.css"/>
    <script type="text/javascript" src="pastTournament.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Previous Tournaments</title>
</head>

<body onload="load()">
  <?php
    require 'header.php';
    echoHeader();
  ?>
  <div id="mydata">
    <h1>PREVIOUS TOURNAMENTS</h1>
    <p>Click a tournament to view the table.</p>
      <table id="myTableData" class="pastTournaments expands" border="1" cellpadding="2">
        <tr>
          <th>Tournament Name</th>
          <th>1st Place</th>
        </tr>
        <?php

        require 'tableFunctions.php';
        require 'fileHandling.php';

        //Read text from file and split into the names of different players
        $playerFileText = readFileText("../data/playerNames.txt");
        $playerFileText = preg_replace('/\s+/', '', $playerFileText);
        $playerNames = explode(",",$playerFileText);

        //Read text from file and split into the details of different tournaments
        $pastTournamentsText = readFileText("../data/pastTournamentNames.txt");
        $records = explode("\n",$pastTournamentsText);
        $rowNum = 1;


        //Loop through records of all tournaments from most recent
        for ($i=count($records) -1; $i >= 0; $i--) {
          //Ensure the record is not empty and contains data to process
          if(strlen($records[$i]) > 0){
            $fields = explode("|",$records[$i]);

            //Read file containing information about tournament and split into records
            //of players involved and match results
            $tournamentFileName = "../data/tournaments/tournament". $fields[1] . ".txt";
            $tournamentFileName = preg_replace('/\s+/', '', $tournamentFileName);
            $tournamentFileText = readFileText($tournamentFileName);
            $tournamentRecords = explode("\n", $tournamentFileText);

            //Split first record to get players involved
            $playerNumbers = explode(",", $tournamentRecords[0]);

            //Initialises 2D array to store stats for players
            $playerStats = array();
            for ($j=0; $j < count($playerNumbers); $j++) {
              $stats = array("Points"=>0, "Wins"=>0, "Draws"=>0, "Losses"=>0, "GoalsFor"=>0, "GoalsAgainst"=>0);
              $playerStats[($playerNumbers[$j])] = $stats;
            }
            //Call function which will process remaining records and complete
            //player stats array showing the players performance in the tournament
            $playerStats = processTournamentData($playerNumbers, $playerStats, $tournamentRecords);
            //Use quick sort function which will sort the player stats into the correct
            //order based on performance.
            $playerNumbers = quick_sort($playerNumbers, $playerStats, ["Points", "GoalDiff", 'GoalsFor']);


            //Output row which summarises tournament information
            echo "<tr id=\"tr$rowNum\" onClick=\"Javacsript:expandTournament(this)\"><td>";
            echo $fields[0] ."</td>";
            //This field defines the player first place
            echo "<td>" . $playerNames[$playerNumbers[0]] . "</td>";

            $rowNum += 1;

            //This row contains the tournament table corresponding to the above row
            //It is hidden until the player clicks on the above row
            echo "<tr id=\"tr$rowNum\"  style=\"display: none\"><td class=\"tableHolder\" colspan=\"2\">\n";
            //Calls function which outputs the table
            echo_inner_table($playerNumbers, $playerStats, $playerNames);
            //Link takes user to page which will display the results and the tournament
            echo "<a href=\"viewTournament.php?tournNum=" . $fields[1] . "\">Click here to view/enter results</a></td></tr>\n";
            echo"</tr>\n";

            $rowNum += 1;
            }
          }
        ?>
      </table>
     &nbsp;<br/>
  </div>

</body>

</html>
