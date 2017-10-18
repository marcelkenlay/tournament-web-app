function toggleNameTextBox(playerNo){
  var div = document.getElementById('testingDiv');

  var selectID = "playerSelect" + playerNo;
  var select = document.getElementById(selectID);

  var textboxID = "playerName" + playerNo;
  var textbox = document.getElementById(textboxID);

  var selection = select.value;
  div.innerHTML = textboxID.value;

  if (selection == "new"){
    textbox.style.display = 'default';
  } else {
    textbox.style.display = 'none';
  }
}

function validateNumberAndName(){
    alert("Name must be filled out");
    return false;
  var form = document.forms["tournamentInfo"];
  var numPlayers = document.getElementById('noOfPlayersIn')
  var usedNames = [];
  if (numPlayers == "" || numPlayers <= 0){
    alert("Number of players must be greater than 0")
    return false;
  }
  var x = document.forms["tournamentInfo"]["noOfPlayers"].value;
    if (x == "") {
        alert("Name must be filled out");
        return false;
    }
}
  return true;
}

function validateForm(){
  prevPlayerNames = "";
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     pastTournamentNames = xhttp.responseText;
     updateTournamentList();
   }
  };
  xhttp.open("GET", "playerNames.txt", true);
  xhttp.send();
  var usedNames = [];
  var form = document.tournamentInfo;
  var numPlayers = form.noOfPlayers.value;
  var usedNames = [];
  for (var i = 0; i < numPlayers; i++) {
    var pISelectID = "playerSelect" + i;
    var playerISelect = document.getElementById('pISelectID').value;
    var pINameID = "playerName" + i;
    var playerIName = document.getElementById('pINameID').value;
    if (playerISelect == "new"){
      if (playerIName == ""){
        alert("No name given for player " + i)
        return false;
      }
      playerNames = prevPlayerNames.split(",")
      for (var j = 0; j < playerNames.length; j++) {
        if (playerNames[j] == playerIName){
          alert("Player " + i + " name is invalid as it already exists.")
          return false
        }
      }
    }
    for (var j = 0; j < usedNames.length; j++) {
      if(usedNames[j] == playerIName){
        alert("Player " + i + " name is invalid as it is a duplicate.")
        return false;
      }
    }
    array_push(usedNames, playerIName);
  }
  return true;
}

function load(){

}
