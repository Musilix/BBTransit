$(document).ready(function(){
	let meatyElements = $("#sides-wrap");
	let navBarHeight = $("#top-nav-bar").height();
	let windowHeight = $(window).height();

	let percentageOfNav = (navBarHeight / windowHeight) * 100;
	let percentageOfReal = 100 - percentageOfNav;

	meatyElements.css({
		height: percentageOfReal + "%"
	});
});
