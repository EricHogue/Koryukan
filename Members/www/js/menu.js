

$(function() {
	$('ul.mainMenu li').hover(
	    function() {
	        $(this).addClass('ui-state-hover');
	    }, 
	    function() {
	        $(this).removeClass('ui-state-hover');
	    }
	);
});
