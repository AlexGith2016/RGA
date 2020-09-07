var default_content="";
var lasturl="";
///////////////////////////////////////////////////when hash change///////////////////////////////////////////////////////////////
function locationHashChanged() {
    console.log("ha cambiado la url hash:"+window.location.hash);
    checkURL();
}
///////////////////////////////////////////////////look at hash///////////////////////////////////////////////////////////////
function checkURL(hash){
	if(!hash) 
		hash=window.location.hash;
		
	if(hash != lasturl){
		lasturl=hash;
		if(hash=="" || hash == "#"){
			//$('#contenidoPaginas').html(default_content);
			$('.cargador').css({visibility: "hidden", display: "none" });
		}else
			loadPage(hash);
	}
}
///////////////////////////////////////////////////LOAD PAGES///////////////////////////////////////////////////////////////
function loadPage(url){
	$('.cargador').css({visibility: "visible", display: "block" });
	url=url.replace('#','');
	console.log("url change: "+url);
	$('#contenidoPaginas').html("");
	$.ajax({
		type: "POST",
		url: 'core/Controllers/GestorController.php',
		data: 'page='+url
	}).done(function (info) { //informacion que el controller le reenvia a la vista
        if(parseInt(info)!=0){
        	$('#contenidoPaginas').hide();
			$('#contenidoPaginas').html(info);
		}else{
			console.log("ERRORPAGINA");
		}
    }).fail(function () {
        console.log("ERRORCONECTAR");
    });
}
///////////////////////////////////////////////////do visible load gif///////////////////////////////////////////////////////////////
function cargar(){
	$(".loader-img").fadeOut();
	$(".loader").delay(1000).fadeOut("slow");
}
///////////////////////////////////////////////////before load DOM///////////////////////////////////////////////////////////////
jQuery(window).load(function() {
	cargar();
});

////////////////////////////////////////////////////////after load DOM//////////////////////////////////////////////////////
jQuery(document).ready(function() {
	//Load page with AJAX
    window.onhashchange = locationHashChanged;
	checkURL();
	default_content = $('#contenidoPaginas').html();
    /***************************************************************
        Wow
    ***************************************************************/
    new WOW().init();
    
	// show/hide menu
	$('.show-menu a').on('click', function(e) {
		e.preventDefault();
		$(this).fadeOut(100, function(){ $('nav').slideDown(); });
	});
	$('.hide-menu a').on('click', function(e) {
		e.preventDefault();
		$('nav').slideUp(function(){ $('.show-menu a').fadeIn(); });
	});
	var agente = window.navigator.userAgent;
    var nav;
	var navegadores = ["Chrome", "Firefox", "Safari", "Opera", "Trident", "MSIE", "Edge"];
	for(var i in navegadores){
	  if(agente.indexOf( navegadores[i]) != -1 ){
	      if(navegadores[i] == "Trident" || navegadores[i] == "MSIE" || navegadores[i] == "Edge")
	    	nav = true;
	    	break;
	  }
	}
	if (nav)
		$('#cssmenu .has-sub ul').css('position','relative');
	
	/***********************************************************
	    CHARTS
	************************************************************/
	if(window.location.hash =="")
		graficar(1);
	else
		console.log("location: "+window.location.hash);
});
function chartM(meses, totales) {
	$("#myBarChart").html("");
	$('#myBarChart').siblings('iframe').remove();
	var ctx = document.getElementById("myBarChart").getContext("2d");
	window.graf = null;
	window.graf = new Chart(ctx, {
		type: 'bar',
		data:{
			labels: meses,
		    datasets: [{
		    	label: "Total",
		    	backgroundColor: [
	            	'rgba(255, 99, 132, 0.8)',
	            	'rgba(54, 162, 235, 0.8)',
	            	'rgba(255, 206, 86, 0.8)',
	        		'rgba(75, 192, 192, 0.8)',
	        		'rgba(153, 102, 255, 0.8)',
	        		'rgba(255, 159, 64, 0.8)'
	        	],
		    	borderColor: "rgba(2,117,216,1)",
		    	data: totales,
		    }],
		},
		options: {
			scales: {
			  xAxes: [{
			    time: {
			      unit: 'mes'
			    },
			    gridLines: {
			      display: true
			    }
			  }]
			},
			legend: {
			  display: false
			}
		}
	});
}

function graficar(opcion){
	var mes1 = 0, mes2 = 0, meses =[], mesesNum =[], anio = $("#anioE").val();
	if($("#periodoE").val() == 1){
		mes1 = 1;
		mes2 = 6;
		meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio"];
		mesesNum = [1, 2, 3, 4, 5, 6];
	}else{
		mes1= 7
		mes2= 12;
		meses = ["Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
		mesesNum = [7, 8, 9, 10, 11, 12];
	}
	var totales = [];
	//info = JSON.parse(info);
	for (var i = 0; i<mesesNum.length;i++) {
		var igual = true;
		totales[i] = i+1*10
	}
	/* if(info.length <= 0)
		totales = [0,0,0,0,0,0]; */
	
	if(opcion == 1){//al cargar
		chartM(meses, totales);
	}else if(opcion == 2){//al hacer click
		window.graf.data.labels.splice(0);
		window.graf.data.datasets.forEach((dataset) => { dataset.data.splice(0); });
		window.graf.data.labels = meses;
		window.graf.data.datasets.forEach((dataset) => { dataset.data = totales; });
		window.graf.update();
	}
	/* $.ajax({
		type: "GET",
		url: 'core/Controllers/EstudianteController.php',
		data: {"mesInicio":mes1, "mesFin":mes2, "anio": anio, "opcion": 4}
	}).done(function (info) { //informacion que el controller le reenvia a la vista
        var totales = [];
        info = JSON.parse(info);
        for (var i = 0; i<mesesNum.length;i++) {
			var igual = true;
			for (var prop in info) {
				if(parseInt(info[prop]["fecha"]) == mesesNum[i]){
					totales[i] = info[prop]["num"];
					igual = true;
					break;
				}else
					igual = false;
	  		}
	  		if(igual == false)
	  			totales[i] = 0;
		}
		if(info.length <= 0)
			totales = [0,0,0,0,0,0];
		
        if(opcion == 1){//al cargar
        	chartM(meses, totales);
        }else if(opcion == 2){//al hacer click
        	window.graf.data.labels.splice(0);
        	window.graf.data.datasets.forEach((dataset) => { dataset.data.splice(0); });
		    window.graf.data.labels = meses;
		    window.graf.data.datasets.forEach((dataset) => { dataset.data = totales; });
        	window.graf.update();
        }
        
   }).fail(function () {
        mostrarMensaje("#dialogM", "ERROR", "¡No se logró realizar la acción!", "danger");
   }); */
}

function LoadDataTablesScripts(callback){
	function LoadDatatables(){
		$.getScript('Views/assets/dataTables/jquery.dataTables.js', function(){
			$.getScript('Views/assets/dataTables/dataTables.bootstrap.js', function() {
				$.getScript('Views/assets/dataTables/dataTables.buttons.js', function() {
					$.getScript('Views/assets/dataTables/buttons.bootstrap.js', function(){
						$.getScript('Views/assets/dataTables/buttons.html5.min.js',callback);
					});
				});
			});
		});
	}
	if (!$.fn.dataTables){
		LoadDatatables();
	}else {
		if (callback && typeof(callback) === "function") {
			callback();
		}
	}
}
//
//Cambiar idioma a datatable
//
var idioma_esp = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar registros _MENU_",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar->",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}

/////////////////////////////////////////jQuery(document).ready down selectFile()/////////////////////////////////////////////////
/*
Testimonials
*/
/*$('.testimonial-active').html('<p>' + $('.testimonial-single:first p').html() + '</p>');
$('.testimonial-single:first .testimonial-single-image img').css('opacity', '1');

$('.testimonial-single-image img').on('click', function() {
$('.testimonial-single-image img').css('opacity', '0.5');
$(this).css('opacity', '1');
var new_testimonial_text = $(this).parent('.testimonial-single-image').siblings('p').html();
$('.testimonial-active p').fadeOut(300, function() {
	$(this).html(new_testimonial_text);
	$(this).fadeIn(400);
});
});*/

////////////////////////////////////////////jquery(windows).load down windows_loader/////////////////////////////////////////////////
/*
    Portfolio
*/
/*$('.portfolio-masonry').masonry({
	columnWidth: '.portfolio-box', 
	itemSelector: '.portfolio-box',
	transitionDuration: '0.5s'
});

$('.portfolio-filters a').on('click', function(e){
	e.preventDefault();
	if(!$(this).hasClass('active')) {
    	$('.portfolio-filters a').removeClass('active');
    	var clicked_filter = $(this).attr('class').replace('filter-', '');
    	$(this).addClass('active');
    	if(clicked_filter != 'all') {
    		$('.portfolio-box:not(.' + clicked_filter + ')').css('display', 'none');
    		$('.portfolio-box:not(.' + clicked_filter + ')').removeClass('portfolio-box');
    		$('.' + clicked_filter).addClass('portfolio-box');
    		$('.' + clicked_filter).css('display', 'block');
    		$('.portfolio-masonry').masonry();
    	}
    	else {
    		$('.portfolio-masonry > div').addClass('portfolio-box');
    		$('.portfolio-masonry > div').css('display', 'block');
    		$('.portfolio-masonry').masonry();
    	}
	}
});*/

//$(window).on('resize', function(){ $('.portfolio-masonry').masonry(); });

// image popup	
/*$('.portfolio-box-text').magnificPopup({
	type: 'image',
	gallery: {
		enabled: true,
		navigateByImgClick: true,
		preload: [0,1] // Will preload 0 - before current, and 1 after the current image
	},
	image: {
		tError: 'The image could not be loaded.',
		titleSrc: function(item) {
			return item.el.find('p').text();
		}
	},
	callbacks: {
		elementParse: function(item) {
			item.src = item.el.parent('.portfolio-box-text-container').siblings('img').attr('src');
		}
	}
});*/