var larkNav = function() {
	this.lastName = null;
	
	
	this.nav = {};
	this.init = function() {
		var obj = $("#nav");
		this.nav.ofs = obj.offset();
		this.nav.width = obj.width();
		this.nav.height = obj.height();
		
		this.bindEvent();
	};
	
	this.shareOpened = function() {
		
	};
	this.friendOpened = function() {
		this.checkLogin("#friend .friend_list", "#friend .please_login");
		
	};
	this.messageOpened = function() {
		if(user.userInfo) $("#message .tab li").show();
		
	};
	this.sayOpened = function() {
		this.checkLogin("#say .tab", "#say .please_login");
		
	};
	this.regOpened = function() {
		
	};
	this.loginOpened = function() {
		
	};
	this.infoOpened = function() {
		this.checkLogin("#info .info_content", "#info .please_login");
	};
}

larkNav.prototype = {
	show: function(myName) {
		var obj = $("#"+myName);
		if(!obj.length) return false;
		
		if(myName != 'reg' && myName != 'login') {
			this.lastName = myName;
		}
		
		obj.is(":visible") ? this.close(obj) : this.open(obj, myName+'Opened');
	},
	
	open: function(obj, callback) {
		var _this = this;
		this.closeOther($(obj).attr("class"));
		
		$(obj).css({"top":"0px", "left":this.nav.ofs.left+"px"})
		.show()
		.animate({"top":(this.nav.ofs.top+this.nav.height+4)+"px"}, 200, function() {
			
			if(callback && typeof(_this[callback] == "function")) _this[callback]();
		});
	},
	
	close: function(obj, callback) {
		$(obj).animate({"top":"0px"}, 200, function() {
			$(this).hide();
			if(callback) callback();
		});
	},
	
	closeOther: function(myName) {
		$("#content").children("div").each(function() {
			if($(this).attr("class") != myName && $(this).is(":visible")) {
				$(this).fadeOut();
			}
		});
	},
	
	checkLogin: function(showId, hideId) {
		if(user.userInfo) {
			$(showId).show();
			$(hideId).hide();
		} else {
			$(showId).hide();
			$(hideId).show();
		}
	}
}

larkNav.prototype.bindEvent = function() {
	var _this = this;
	
	// 导航切换
	$("#nav .operators li").click(function() {
		if($(this).attr("class") == "current") {
			$(this).removeClass();
		} else {
			$("#nav .operators li.current").removeClass();
			$(this).addClass("current");
		}
		_this.show($(this).attr("name"));
	});
	
	$("a.user_reg, a.user_login").click(function() {
		$("#nav .operators li.current").removeClass();
		_this.show($(this).attr("name"));
		return false;
	});
	
	$("#nav .avatar").click(function() {
		$("#nav .operators li.current").removeClass();
		_this.show("info");
	});
}