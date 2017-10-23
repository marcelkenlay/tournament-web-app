<?php
  function echoHeader($page){
    echo "<div class=\"wrapper\">
      <div class=\"navigation\">
        <ul class=\"nav\">";
    echo "<li" . ($page == 0 ? " class=\"active\"" : "") .
          "><a href=\"index.php\">Past Tournaments</a></li>";
    echo "<li" . ($page == 1 ? " class=\"active\"" : "") .
          "><a href=\"overallLeaderboard.php\">Leaderboard</a></li>";
    echo "<li" . ($page == 2 ? " class=\"active\"" : "") .
          "><a href=\"playerRecord.php\">PLayer Record</a></li>";
    echo "<li" . ($page == 3 ? " class=\"active\"" : "") .
          "><a href=\"addTournament.php\">Add Tournament</a></li>";
    echo "</ul></div></div>";
  }
 ?>
