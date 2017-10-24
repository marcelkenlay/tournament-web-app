var pastTournamentNames = [];
var prevPlayers = [];

function toggleNameTextBox(playerNo){

  var selectID = "playerSelect" + playerNo;
  var select = document.getElementById(selectID);

  var textboxID = "playerName" + playerNo;
  var textbox = document.getElementById(textboxID);

  var selection = select.value;

  if (selection == "new"){
    textbox.style.display = 'default';
  } else {
    textbox.style.display = 'none';
  }
}



function validateNumberAndName(){
  var form = document.forms["tournamentInfo"];
  var numPlayers = document.getElementById('noOfPlayers').value;
  var usedNames = [];
  if (numPlayers == "" || numPlayers.value <= 0){
    alert("Number of players must be greater than 0");
    return false;
  }

  var tournName = document.forms["tournamentInfo"]["tournamentName"].value;

  if (tournName == "") {
    alert("Name must be filled out");
    return false;
  }

  for (var i = 0; i < pastTournamentNames.length; i++) {
    if (pastTournamentNames[i] == tournName){
          alert("Name has already been used.");
          return false;
    }
  }
  return true;
}


function validateForm(){
  var form = document.forms["tournamentInfo"];
  var numPlayers = form['numPlayers'].value;

  var usedNames = [];
  for (var i = 1; i <= numPlayers; i++) {
    var pISelectID = "playerSelect" + String(i);
    var playerISelect = document.getElementById(pISelectID).value;
    var pINameID = "playerName" + String(i);
    var playerIName = document.getElementById(pINameID).value;

    var playerName = playerISelect;

    if (playerISelect === "new"){
      playerName = playerIName;

      if (playerIName == ""){
        alert("No name given for player " + i);
        return false;
      }

      for (var j = 0; j < prevPlayers.length; j++) {
        if (prevPlayers[j] == playerIName){
          alert("Player " + String(i) + " name is invalid as it already exists.");
          return false;
        }
      }
    }

    for (var j = 0; j < usedNames.length; j++) {
      // alert(">>" + usedNames[j]);
      if(usedNames[j] == playerName){
        alert("Player " + String(i) + " name is invalid as it is a duplicate.");
        return false;
      }
    }

    usedNames.push(playerName);

  }
  return true;
}



function load(){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     var tournamentFileText = xhttp.responseText;
     var pastTournamentRecords = tournamentFileText.split("\n");
     for (var i = 0; i < pastTournamentRecords.length; i++) {
       var tournFields = pastTournamentRecords[i].split("|");
       if (tournFields.length > 0){
         pastTournamentNames.push(tournFields[0]);
       }
     }
   }
  };
  xhttp.open("GET", "../data/pastTournamentNames.txt", true);
  xhttp.send();

  var xhttp2 = new XMLHttpRequest();
  xhttp2.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     var playerFileText = xhttp2.responseText;
     prevPlayers = playerFileText.split(",");
   }
  };
  xhttp2.open("GET", "../data/playerNames.txt", true);
  xhttp2.send();
}
