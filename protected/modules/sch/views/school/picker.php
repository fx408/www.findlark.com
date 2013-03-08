<div id="google_map" style="height:100%; width:100%"></div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeTbbaUbaoslV0OY-Jyoex6kfMBXRRIZk&sensor=false&libraries=panoramio"></script>
<script type="text/javascript" src="/static/js/school/map.js"></script>
<script type="text/javascript">
	$(function() {
		var map = new larkMap();
		map.afterMapLoad = function() {
			window.parent.positionCallback({ib: this.position.latitude, jb: this.position.longitude});
			
			this.showDragMark('拖动我，用以标记校区位置<br><button id="close_picker">选择</button>', function(e) {
				window.parent.positionCallback(e.latLng);
			});
		};
		
		
		$("body").on("click", "#close_picker", function() {
			window.parent.$.colorbox.close()
		});
		
		map.init();
	});
	
	
	
</script>