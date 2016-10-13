$(document).ready(function(){

$("#submit").click(function(){
var company = $("#company").val();

var dataString = 'company='+ company;

// Returns successful data submission message when the entered information is stored in database.
if(company=='')
{
alert("ALERT: Please type in a company name");
}
else
{
// AJAX Code To Submit Form.
$.ajax({
type: "POST",
url: "/php/screener.php",
data: dataString,
cache: false,
success: function(result){
$("#results-table").css("display", "block");
$("#results-table").html(result);
alert(result);
}
});
}
return false;
});
});