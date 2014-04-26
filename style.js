/*
 *  style.js contains all styles that are enecessary for
 *  javascript to handle, such as parent selectors.
 */
 
 $(window).load(function() {
	$(document).on('click', '#answers ul li input', markSelected);
	
	function markSelected() {
		$("#answers ul li label").removeClass("selected");
		$(this).parent().addClass("selected");
	}
});