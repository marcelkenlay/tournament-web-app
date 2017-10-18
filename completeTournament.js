function updateBackground(obj, originalValue){
  var testDiv = document.getElementById('testing');
  testDiv.innerHTML = "uk";
  if (obj.value != originalValue){
    obj.style.background = "#b3ccff";
  } else {
    obj.style.background = "#FFFFFF";
  }
}

function load() {
  var idd = "testing";
  var testDiv = document.getElementById(idd);
  testDiv.innerHTML = "oof";
}
