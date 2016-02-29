
/*
	Copyright: (c) 2015 TheGrue, http://thegrue.org/
*/


jQuery(document).ready(function($) {


/*  DropDown Animation
/* ------------------------------------ */
	$('#gruemenu ul.sub-menu').hide();
	$('#gruemenu li').hover( 
		function() {
			$(this).children('ul.sub-menu').fadeIn(300);
		}, 
		function() {
			$(this).children('ul.sub-menu').hide();
		}
	);	
	

/*  Fixed Navigation Menu starting at 300px from top
/* ------------------------------------ */	
	$(window).bind('scroll', function () {
		if ($(window).scrollTop() > 300) {
			$('#gruemenu').addClass('gruefixed');
		} else {
			$('#gruemenu').removeClass('gruefixed');
		}
	});
	
/*  Touch Events
/* ------------------------------------ */	
      $(window).touchwipe({
        wipeLeft: function() {
          // Close
          $.sidr('close', 'sidr-main');
        },
        wipeRight: function() {
          // Open
          $.sidr('open', 'sidr-main');
        },
        preventDefaultEvents: false
      });	
	
});
