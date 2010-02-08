$fsjq = jQuery.noConflict();

$fsjq(document).ready(function() {
	
	
	
	
	// *****************************
	// Config panel animations
	// *****************************
	
	// Hide config panel
	$fsjq('#widgetConfigPanel').animate({height: '5px', padding: '0'}, 50);
	
	// Shows config panel on mouse over
	$fsjq('#widgetConfigPanel').mouseenter(function(){
		$fsjq('#widgetConfigPanel').animate({height: '150px', padding: '5px'}, 500);			
	});
	
	// Click on the submit button (config panel)
	$fsjq('#wp-fscharts-pi-submit').click(function(){
		$fsjq('#widgetConfigPanel').animate({height: '5px', padding: '0'}, 500);
	});
	
	
	
	// *****************************
	// Buttons replacement
	// *****************************
	
});
