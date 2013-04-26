var larkMap = function() {
	this.position = {latitude: 31.1, longitude:104.3};
	this.defaultZoom = 5, // zoom 默认值
	this.map = null; // google map 对象
	this.infoWindownLifeTime = 8000; // marker infoWindow 最大显示时间 (ms)
	this.socket;
	this.getPositionSuccess = false;
	this.helloIsSay = false;
	this.mapId = 'google_map';
	
	this.markerList = []; // 用于保存标记
	
	this.dragMark = null;
	this.dragEndCallback = function(event){};
	this.dragMarkInfoWindow = null;
}

larkMap.prototype = {
	init: function() {
		var _this = this;
		var winHeight = $(window).height(), winWidth = $(window).width();
		$("#google_map").css({"height":winHeight+"px"});

		try{
			var geolocationError = function(err) { // 定位失败
				console.log(err);
				_this.getPositionSuccess = false;
				_this.showGoogleMap();
			},
			geolocationSuccess = function(data) { // 定位成功
				_this.position.latitude = data.coords.latitude;
				_this.position.longitude = data.coords.longitude;
				_this.getPositionSuccess = true;
				_this.defaultZoom = 8;
				_this.showGoogleMap();
			};
			
			navigator.geolocation.getCurrentPosition(geolocationSuccess, geolocationError);
		}catch(e) {
			_this.showGoogleMap();
		}
		
		this.bindEvent();
	},
	
	showGoogleMap: function() {
		var mapTypes = ['ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN'];
		var markLatlng = new google.maps.LatLng(parseFloat(this.position.latitude), parseFloat(this.position.longitude));
		var mapOptions = {
			center: markLatlng,
			zoom: this.defaultZoom,
			mapTypeId: google.maps.MapTypeId[mapTypes[3]]
		};
		this.map = new google.maps.Map(document.getElementById(this.mapId), mapOptions);
		
		var panoramioLayer = new google.maps.panoramio.PanoramioLayer();
		panoramioLayer.setUserId('7046414');
		panoramioLayer.setMap(this.map);
		
		var _this = this;
		google.maps.event.addListener(panoramioLayer, 'click', function(event) {
			event.infoWindowHtml = '<div class="info_window_div">'
				+ '<div style="font-size:14px; font-weight:700">'+event.featureDetails.title+'</div>'
				+ '<div style="margin:5px 0px; width:240px; height:160px; overflow:hidden"><a href="javascript:;" class="thumb_image">'
				//+ '<img src="https://mw2.google.com/mw-panoramio/photos/small/'+event.featureDetails.photoId+'.jpg"></a></div>'
				+ '<img src="http://static.panoramio.com.storage.googleapis.com/photos/small/'+event.featureDetails.photoId+'.jpg"></a></div>'
				
				+ '<div style="height:20px;">'
				+ '<div style="float:left"><a href="'+event.featureDetails.url+'" target="_blank"><img src="https://maps.gstatic.com/intl/en_us/mapfiles/iw_panoramio.png"></a></div>'
				//+ '<div style="float:right"><a href="https://www.panoramio.com/user/7046414" target="_blank">'+event.featureDetails.author+'</a>'
				+ '<div style="float:right"><a href="http://hi.baidu.com/findlark" target="_blank">FindLark</a>'
				+ '</div></div>';
				
			_this.loadImageList('/site/image/pid/'+event.featureDetails.photoId);
		});
		
		google.maps.event.addListener(this.map, 'click', function(event) {
			//console.log(event);
		});
		
		this.createDragMark(markLatlng);
		this.afterMapLoad();
	},
	
	// 地图加载完成之后
	afterMapLoad: function() {
		var socket = io.connect(mapSocketConnectString),
				_this = this;
		socket.on('news', function (data) {
			
			console.log(data);
			
			if(_this.helloIsSay == false) {
				_this.addMark(data, true);
				_this.helloIsSay = true;
			}
		});
		
		this.socket = socket;
		this.loadMark();
	},

	// 加载标记
	loadMark: function() {
		var _this = this;
		
		$.get('/site/mark', {}, function(data) {
			var c = 0;
			for(var i in data) {
				setTimeout(function() {
					_this.addMark(data[i]);
				}, Math.floor(c/30)*200);
			}
		}, 'json');
	},
	
	// 加载图片列表
	loadImageList: function(url) {
		var _this = this;
		
		$.get(url, {}, function(data) {
			var html = '';
			for(var i in data) {
				html += _this.createImageHtml(data[i]);
			}
			$("#image_list").html(html);
			
			$("#image_list a").fancybox({
				cyclic:true,
				speedOut: 10,
				transitionOut:'none'
			});
		}, 'json');
	},
	
	createImageHtml: function(data) {
		var t = data.title == null ? '' : data.title;
		return '<a href="'+data.src+'" rel="rel_'+data.panoramio_id+'" title="'+t+'">'+data.panoramio_id+'</a>';
	},

	// 在地图上 添加一个标记
	addMark: function(data, showInfoWindow) {
		if(data.latitude==0 || data.longitude==0) {
			var center = this.createLatlng();
			data.latitude = center.Ya;
			data.longitude= center.Za;
		}
		var markLatlng = new google.maps.LatLng(parseFloat(data.latitude), parseFloat(data.longitude));
		
		var marker = this.createSayMark(data, markLatlng);
		var infoWindow = null, _this = this;
	
		// 标记点击事件
		google.maps.event.addListener(marker, 'click', function() {
			if(infoWindow == null) {
				var contentString = '<div class="mark_content"><h3>'+data.title+'</h3>'
				+ data.content
				+ '</div><div class="speak"><a href="javascript:;">我也说两句</a></div>';
				
				infoWindow = new google.maps.InfoWindow({
					content: contentString
				});
			}
			
			infoWindow.open(_this.map, marker);
			setTimeout(function() {
				infoWindow.close();
			}, _this.infoWindownLifeTime);
		  
		  if(marker.getAnimation() == null) {
		    marker.setAnimation(google.maps.Animation.BOUNCE);
		    setTimeout(function() {
		    	marker.setAnimation(null);
		    }, 2000);
		  }
		});
		
		setTimeout(function() {
			if(showInfoWindow) google.maps.event.trigger(marker, 'click');
		}, 500);
		
		this.markerList.push(marker);
	},
	
	// 创建说点标记
	createSayMark: function(data, latLng) {
		var image = new google.maps.MarkerImage('/static/images/'+data.icon+'.png',
			new google.maps.Size(45, 28),
			new google.maps.Point(0,0),
			new google.maps.Point(0, 28)
		);
		
		var marker = new google.maps.Marker({
			position: latLng,
			map: this.map,
			icon: image,
			title: data.title
		});

		return marker;
	},

	// 添加一个 可拖动的 的标记
	createDragMark: function(markLatlng) {
		var SymbolPathList = ['BACKWARD_CLOSED_ARROW', 'BACKWARD_OPEN_ARROW', 'CIRCLE', 'FORWARD_CLOSED_ARROW', 'FORWARD_OPEN_ARROW'];
		var _this = this;
		
		this.dragMark = new google.maps.Marker({
			title: 'Mark!',
			position: markLatlng,
			map: this.map,
			draggable: true,
			visible: false,
			animation: google.maps.Animation.DROP
		});
			
		google.maps.event.addListener(this.dragMark, 'dragend', function(event) {
			console.log(event);
			_this.dragEndCallback(event);
		});
	},

	// 显示 dragMark
	showDragMark: function (title, callback) {
		var center = this.createLatlng();
				
		this.dragEndCallback = callback;
		this.dragMark.setOptions({
			//title: title,
			position: center.latlng,
			visible: true
		});
		
		this.dragMarkInfoWindow = new google.maps.InfoWindow({
			content: title
		});
		this.dragMarkInfoWindow.open(this.map, this.dragMark);
	},
	
	// 位置
	createLatlng: function(isCenter) {
		var center = this.map.getCenter();
		if(!isCenter && this.getPositionSuccess == true) {
			center.Ya = this.position.latitude;
			center.Za = this.position.longitude;
		}
	
		var Latlng = new google.maps.LatLng(parseFloat(center.Ya), parseFloat(center.Za));
		return {Ya:center.Ya, Za:center.Za, latlng:Latlng};
	},

	// 隐藏 dragMark
	hideDragMark: function() {
		if(this.dragMarkInfoWindow != null) {
			this.dragMarkInfoWindow.close();
			this.dragMarkInfoWindow = null;
		}	
	
		this.dragMark.setOptions({
			visible: false
		});
	}
};

larkMap.prototype.bindEvent = function() {
	$("div.mark_content a").live("click", function() {
		$(this).attr("target", "_blank");
		return true;
	});
	
	$(".info_window_div a.thumb_image").live("click", function() {
		$("#image_list a:eq(0)").trigger("click");
		return false;
	});
}