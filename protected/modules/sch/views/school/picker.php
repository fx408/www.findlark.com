<div id="google_map" style="height:100%; width:100%"></div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeTbbaUbaoslV0OY-Jyoex6kfMBXRRIZk&sensor=false"></script>
<script type="text/javascript" src="/static/js/school/map.js"></script>
<script type="text/javascript">
	$(function() {
		var map = new larkMap();
		map.geocoder = new google.maps.Geocoder();
		map.afterMapLoad = function() {
			//window.parent.setLatLng({ib: this.position.latitude, jb: this.position.longitude});
			
			this.showDragMark('<div>拖动我，用以标记校区位置</div><button id="close_picker">选择</button>', function(e) {
				
				var latlng = new google.maps.LatLng(e.latLng.ib, e.latLng.jb);
				
				this.geocoder.geocode({'latLng': latlng}, function(results, status) {
					//console.log(results);
					if(results[0]) {
						window.parent.setAddress(results[0].formatted_address);
						//console.log(results[0].formatted_address);
					}
				});
				
				window.parent.setLatLng(e.latLng);
			});
		};
		map.init();
		
		$("body").on("click", "#close_picker", function() {
			window.parent.$.colorbox.close()
		});
	});
	
</script>