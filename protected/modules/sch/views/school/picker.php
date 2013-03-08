<div id="google_map" style="height:100%; width:100%"></div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeTbbaUbaoslV0OY-Jyoex6kfMBXRRIZk&sensor=false&libraries=geocoder"></script>
<script type="text/javascript" src="/static/js/school/map.js"></script>
<script type="text/javascript">
	$(function() {
		var map = new larkMap();
		map.afterMapLoad = function() {
			window.parent.positionCallback({ib: this.position.latitude, jb: this.position.longitude});
			
			this.showDragMark('拖动我，用以标记校区位置<br><button id="close_picker">选择</button>', function(e) {
				
				var latlng = new google.maps.LatLng(e.latLng.ib, e.latLng.jb);
				
				geocoder.geocode({'latLng': latlng}, function(results, status) {
					
					if(results[1]) console.log(results[1].formatted_address);
				});
				
				window.parent.positionCallback(e.latLng);
			});
		};
		map.init();
		
		$("body").on("click", "#close_picker", function() {
			window.parent.$.colorbox.close()
		});
	});
	
</script>