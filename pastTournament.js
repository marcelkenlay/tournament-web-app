

function expandTournament(obj) {



  var insertedText = document.getElementById('insertedText');

  var table = document.getElementById('myTableData');

  var index = obj.rowIndex;

  var nextRow = table.rows[index + 1];

  if (index % 2 == 0){

  } else if (obj.class == "expanded"){

    for (var i = 1; i < table.rows.length; i += 2) {
      var row = table.rows[i];
      row.class = 'notExpanded';
      row.style.backgroundColor = '#ff2f2f2';

      var nextRow = table.rows[i + 1];
      nextRow.style.display = 'none';

    }


  } else {

    for (var i = 1; i < table.rows.length; i += 2) {
      var row = table.rows[i];
      var rowName = "#tr" + i;
      var nextRowName = "#tr" + (i+1);

      if (i == index){
        $(nextRowName).fadeIn( "slow", function() {
          // Animation complete.
        });
        $('html, body').animate({
          scrollTop: $(rowName).offset().top - 120
        }, 300);
      } else {
        $(nextRowName).fadeOut( "slow", function() {
            row.class = 'notExpanded';
        });
      }

      row.class = i == index ? 'expanded' : 'notExpanded';


      // row.style.backgroundColor = i == index ? '#99b3ff' : '#f2f2f2';

      var nextRow = table.rows[i + 1];
      nextRow.style.display = i == index ? 'table-row' : 'none';
      nextRow.style.backgroundColor = '#99b3ff'

    }

  }



}


function load(){


}
