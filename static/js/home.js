var user, map, sns, nav;

$(function() {
	map = new larkMap();
	//sns = new larkSns();
	//nav = new larkNav();
	//user = new larkUser();
	//user.init();
	map.init();
	
	var winWidth=$(window).width();
	// 显示我要说
	$("div.speak a").live("click", function() {
		var obj = $("#input_speak"), h = obj.height(), w = obj.width(), l = (winWidth-w-2) / 1;
		
		if(obj.is(":hidden")) {
			obj.css({"top":(h*-1 - 2)+"px", "left":l+"px"}).show().animate({"top":"0px"}, 300);
			
			map.showDragMark('<div style="color:#E93D12; margin-top:10px;">你将在这里说话，<br>想改变位置吗？只需要托我过去~</div>', function(e){
				$("input[name=latitude]").val(e.latLng.Ya);
				$("input[name=longitude]").val(e.latLng.Za);
			});
		}
		
		return false;
	});
	
	// 提交我要说
	$("#submit_speak").click(function() {
		sns.sendSpeak();
		return false;
	});
	
	// 取消
	$("#cancel_speak").click(function() {
		map.hideDragMark();
		
		var obj = $("#input_speak"), h = obj.height();
		obj.animate({"top":(h*-1 - 2)+"px"}, 300, function() {
			$(this).hide();
		});
	});
	
});