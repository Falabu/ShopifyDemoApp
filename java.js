$( document ).ready(function() {
	
	
	var i;
	
	for (i = 0; i < 3; i++) {

	
	$(".cardExtend_"+i).hide();
    buttonPress(".downButton_" + i,  ".cardExtend_" + i);
}
	
	
	/*
    $( ".downButton" ).click(function() {
  $( ".cardExtendedInfo" ).slideToggle( "fast", function() {
    // Animation complete.
});
});
*/


function buttonPress(button, content) {
        var bPress = 0;

        $(button).click(function () {

            if (bPress === 0) {
                $(this).css({
                    '-webkit-transform': 'rotate(' + 180 + 'deg)',
                    '-moz-transform': 'rotate(' + 180 + 'deg)',
                    '-ms-transform': 'rotate(' + 180 + 'deg)',
                    'transform': 'rotate(' + 180 + 'deg)'
                });

                bPress = 1;
            }

            else if (bPress === 1) {
                $(button).css({
                    '-webkit-transform': 'rotate(' + 0 + 'deg)',
                    '-moz-transform': 'rotate(' + 0 + 'deg)',
                    '-ms-transform': 'rotate(' + 0 + 'deg)',
                    'transform': 'rotate(' + 0 + 'deg)'
                });

                bPress = 0;
            }

            $(content).slideToggle(500);
        })
    }

});


