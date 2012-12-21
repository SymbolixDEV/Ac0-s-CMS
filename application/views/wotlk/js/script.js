$(document).ready(function(){
	var membership = $("#membership"),
		addition = $("#addition");
	
	addition.css("display", "block");
	addition.css("visibility", "visible");
	addition.hide();
		
	membership.hover(function(){
		addition.slideDown();
	});
	
	membership.mouseleave(function(){
		addition.slideUp();
	});
});