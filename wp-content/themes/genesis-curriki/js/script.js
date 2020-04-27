jQuery(document).ready(function(){
	var jQuerycontainer = jQuery('.isotope-container-fitrows').isotope({
		itemSelector: ".isotope-item",
		layoutMode: "fitRows",
		filter: '.Featured'
	});
	
	jQuery('.filters').on( 'click', 'a', function() {
		jQuery('.filters li').removeClass('active');
        jQuery(this).parent().addClass('active');
	});
	
	jQuery('.filters').on( 'click', 'a:not(.dropdown-toggle)', function() {
		var filterValue = jQuery(this).attr('data-filter');
		jQuerycontainer.isotope({ filter: filterValue });
		return false;
	});
	
	var owlTestimonial = jQuery('.owl-testimonial');
	owlTestimonial.owlCarousel({
		nav: false,
		loop: true,
		dots: true,
		responsive: {
			0: {
				items: 1,
				margin: 15
			},
			768: {
				items: 2,
				margin: 30
			},
			992:{
				items: 3,
				margin: 30
			},
			1600:{
				items: 3,
				margin: 78
			}	
		}
	});
	
	jQuery('[data-mh="testimonialheight"]').matchHeight();

	jQuery('.site-alert').on('closed.bs.alert', function () {
		jQuery('body').addClass('site-alert-closed');
	});
});


