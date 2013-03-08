<div id="google_map" style="height:100%; width:100%"></div>

<link rel="stylesheet" type="text/css" href="/static/js/school/fancybox/jquery.fancybox.css">
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeTbbaUbaoslV0OY-Jyoex6kfMBXRRIZk&sensor=false&libraries=panoramio"></script>

<script type="text/javascript" src="/static/js/school/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="/static/js/school/map.js"></script>
<script type="text/javascript">
	<?php printf("var picList = %s; \n", CJSON::encode($picList));?>
	
	$(function() {
		var map = new larkMap();
		map.init();
	});
</script>