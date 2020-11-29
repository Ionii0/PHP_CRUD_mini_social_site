$(document).ready(function(){
	//on click sign up , hide sign in button and show resitration form
$("#signup").click(function(){
	$("#first").slideUp("slow",function(){
		$("#second").slideDown("slow");
	});
});
// on click sign up , hide registration and show loh in form
$("#signin").click(function(){
	$("#second").slideUp("slow",function(){
		$("#first").slideDown("slow");
	});
});



});