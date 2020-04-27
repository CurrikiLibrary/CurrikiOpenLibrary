jQuery(document).ready(function () {
    var carousel = jQuery('.owl-theme');
    carousel.owlCarousel({
        nav: true,
        loop: true,
        dots: false,
        responsive: {
            0: {
                items: 1,
                margin: 15
            },
            640: {
                items: 2,
                margin: 20
            },
            992: {
                items: 3,
                margin: 20
            }
        }
    });

    var carousel2 = jQuery('.owl-theme2');
    carousel2.owlCarousel({
        nav: true,
        loop: true,
        dots: false,
        responsive: {
            0: {
                items: 1,
                margin: 15
            },
            768: {
                items: 2,
                margin: 20
            }
        }
    });
});