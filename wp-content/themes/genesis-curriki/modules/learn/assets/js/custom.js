// JavaScript Document
var $ = jQuery;
var ww = document.body.clientWidth;
$(document).ready(function() {
$(".menu li a").each(function() {
if ($(this).next().length > 0) {
$(this).addClass("parent");
};
})
$(".toggleMenu").click(function(e) {
e.preventDefault();
$(this).toggleClass("active");
$(".main-nav").slideToggle();
});
adjustMenu();
})
$(window).bind('resize orientationchange', function() {
ww = document.body.clientWidth;
adjustMenu();
});
var adjustMenu = function() {
if (ww <= 1199) {
$(".toggleMenu").css("display", "inline-block");
if (!$(".toggleMenu").hasClass("active")) {
$(".main-nav").hide();
} else {
$(".main-nav").show();
}
$(".menu li").unbind('mouseenter mouseleave');
$(".menu li a.parent").unbind('click').bind('click', function(e) {
// must be attached to anchor element to prevent bubbling
e.preventDefault();
$(this).parent("li").toggleClass("hover");
});
} 
else if (ww > 1199) {
$(".toggleMenu").css("display", "none");
$(".main-nav").show();
$(".menu li").removeClass("hover");
$(".menu li a").unbind('click');
$(".menu li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
// must be attached to li so that mouseleave is not triggered when hover over submenu
$(this).toggleClass('hover');
});
}
}
