var $j = jQuery.noConflict();

$j(document).ready(function(){
	 new Timeframe('calendars', {
        startField: 'start',
        endField: 'end',
        earliest: new Date(),
        resetButton: 'reset' });
});
