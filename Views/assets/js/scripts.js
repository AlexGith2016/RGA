
/******************************************************************************
	Scroll to (navigation)
*****************************************************************************/
function scroll_to(clicked_link, nav_height) {
	var element_class = clicked_link.attr('href').replace('#', '.');
	var scroll_to = 0;
	if(element_class != '.top-content') {
		element_class += '-container';
		scroll_to = $(element_class).offset().top - nav_height;
	}
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 1000);
	}
}

///////////////////////////////////////////////////before load DOM///////////////////////////////////////////////////////////////
jQuery(window).load(function() {
	
	/*****************************************************
		Loader
	*******************************************************/
	$(".loader-img").fadeOut();
	$(".loader").delay(1000).fadeOut("slow");
            $('.show-menu a').click();
});

////////////////////////////////////////////////////////after load DOM//////////////////////////////////////////////////////
jQuery(document).ready(function() {
    
    /***************************************************************
        Wow
    ***************************************************************/
    new WOW().init();
    
    /******************************************************************
	    Navigation
	******************************************************************/
	$('a.scroll-link').on('click', function(e) {
		e.preventDefault();
		$('a.scroll-link').removeAttr('id').removeAttr('style');
		$(this).attr('id','scroll_s').css("background-color","#264653   ");
		scroll_to($(this), $('nav').height());
	});
	// show/hide menu
	$('.show-menu a').on('click', function(e) {
		e.preventDefault();
		$(this).fadeOut(100, function(){ $('nav').slideDown(); });
	});
	$('.hide-menu a').on('click', function(e) {
		e.preventDefault();
		$('nav').slideUp(function(){ $('.show-menu a').fadeIn(); });
	});
    
    /***************************************************************
        Fullscreen backgrounds
    ****************************************************************/
    $('.top-content').backstretch("Views/assets/img/backgrounds/1.jpg");
    var agente = window.navigator.userAgent;
    var nav="";
	var navegadores = ["Chrome", "Firefox", "Safari", "Opera", "Trident", "MSIE", "Edge"];
	for(var i in navegadores){
	  if(agente.indexOf( navegadores[i]) != -1 ){
	      if(navegadores[i] == "Trident" || navegadores[i] == "MSIE" || navegadores[i] == "Edge")
	    	nav = true;
	    	break;
	  }
	}
	if (nav)
		$('.counters-container').backstretch("Views/assets/img/backgrounds/3.jpg");
	else
    	$('.counters-container').backstretch("Views/assets/img/backgrounds/3.jpg");
    $('.our-motto-container').backstretch("Views/assets/img/backgrounds/2.jpg");
	
	/***********************************************************
	    select files example
	************************************************************/
	doInputFile('input[name="cv"], input[name="cartaIntencion"]');
});