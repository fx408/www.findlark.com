var html = '';
for(var i = 0; i < 15; i++) {
	html += '<div class="block"></div>';
}

$("div.line").each(function() {
	$(this).html(html);
	
	var w = $(this).find(".block").length * 220;
	$(this).css({"width":w+"px"});
});

var winWidth = $(window).width();

var maxTime = 80;
var time = [50, 20, 35, 25];

$("div.line").each(function(i) {
	var w = $(this).width();

	run(this, (w-winWidth)*-1, time[i])
});


function run(obj, _left, time, callback) {
	$(obj).animate({left:_left+"px"}, time*1000, function() {
		var w = $(this).width();
		run(obj, _left*-1-w+winWidth, getAnimateTime());
	});
}

function getAnimateTime() {
	return Math.round( Math.random()*40+20 );
}