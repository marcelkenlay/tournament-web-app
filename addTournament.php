<head>
  <link rel="stylesheet" href="stylesheet2.css"/>
  <script type="text/javascript" src="addTournament.js"></script>
<title>Add A Tournament</title>
  <?php
    require 'header.php';
    echoHeader(3);
   ?>
  <h1>Add a new tournament</h1>
  <form name="tournamentInfo" id="tournamentInfo"
  <?php
    if (!empty($_POST["noOfPlayers"])){
      echo "action=\"saveTournament.php\" onsubmit=\"return(validateForm())\"";
    } else {
      echo "onsubmit=\"return validateNumberAndName()\"";
    }
  ?>
  method="post">
  <?php
     $numPlayers = 0;
     if (!empty($_POST["noOfPlayers"])){
       $numPlayers = $_POST["noOfPlayers"];
     }
     if ($numPlayers == 0){
       echo "<div id=\"wrapper\">\n";
       echo "<p class=\"inputLabel\"> Number of players: </p>
            <input id=\"noOfPlayers\" class=\"inputs\" type=\"number\" name=\"noOfPlayers\"><br>\n";
       $date = getdate();
       $shortDate = $date['mday'] . "/" . $date['mon'] . "/" . $date['year'];
       $myfile = fopen("../data/pastTournamentNames.txt", "r") or die("Unable to open file!");
       $fileText = fread($myfile,filesize("../data/pastTournamentNames.txt"));
       fclose($myfile);
       $records = explode("\n",$fileText);
       $name = $shortDate;
       $differential = 2;
       $fileNo=0;
       for ($i=0; $i < count($records); $i++) {
         if(strlen($records[$i]) > 0){
           $fields = explode("|",$records[$i]);
           if (strcmp($fields[0], $name) == 0){
             if (strcmp($name, $shortDate) == 0){
             } else {
               $differential += 1;
             }
             $name = $shortDate . " " . $differential;
           }
           $fileNo = (int) $fields[1];
           $fileNo += 1;
         }
       }
       echo "<p class=\"inputLabel\"> Tournament Name: </p>
              <input class=\"inputs\" type=\"string\" name=\"tournamentName\" value=\"". $name . "\"><br>\n";
       echo "</div>\n";
     } else {
       $myfile = fopen("../data/playerNames.txt", "r") or die("Unable to open file!");
       $fileText = fread($myfile,filesize("../data/playerNames.txt"));
       fclose($myfile);
       $records = explode(",",$fileText);

       $tournamentFileName = $_POST["tournamentName"];

       echo "<input name=\"tournamentName\" type=\"hidden\" value=\"" . $tournamentFileName . "\">\n";
       echo "<input name=\"numPlayers\" type=\"hidden\" value=\"" . $numPlayers . "\">\n";

       echo "<div id=\"wrapper\">";
       for ($i=1; $i <= $numPlayers; $i++) {
         echo "<div class=\"playerSelect\">";
         echo "Player $i:";
         echo "<select class=\"inputs\" onchange=\"toggleNameTextBox($i)\" name=\"playerSelect$i\" id=\"playerSelect$i\">";
         echo "<option value=\"new\">New Player</option>";
         for ($j=0; $j < count($records); $j++) {
           echo "<option value=\"$records[$j]\">$records[$j]</option>";
         }
         echo "</select>";
         echo "<input class=\"inputs\" type=\"text\" name=\"playerName$i\" id=\"playerName$i\"/>";
         echo "</div>";
         echo "<br>";
       }
       echo "</div>";
     }
   ?>
   <input type="submit" value="Submit">
  </form>
  <div id="testingDiv">ok</div>
</body>
