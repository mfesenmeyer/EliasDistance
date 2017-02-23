$(document).ready(function(){

var availableTags=[];

$.getJSON( "/php/companyNames.php", function( data ) {
          var i=0;
         for(i=0;i<data.length;i++){
            availableTags[i]=data[i];
        }
});

    $( "#company" ).autocomplete({
      source: availableTags
    });

});