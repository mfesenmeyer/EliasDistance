$(document).ready(function(){

// Submit button on click event handler
$("#submit").click(function(){
var company = $("#company").val();
var amount = $("#amount").val();
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
data: {
        company: company,
        amount: amount
},
cache: false,
success: function(result){
alert(result);
//$("#results-table").css("display", "block");
$("#results-table").html(result);
}
});
}
return false;
});


// Enter Key keypress event handler
$('#company').keypress(function (e) {
 var key = e.which;
 if(key == 13)  // the enter key code
  {
		var company = $("#company").val();
		var amount = $("#amount").val();
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
		data: {
		        company: company,
		        amount: amount
		},
		cache: false,
		success: function(result){
		alert(result);
		//$("#results-table").css("display", "block");
		$("#results-table").html(result);
		}
		});
		}
  }
});  


});