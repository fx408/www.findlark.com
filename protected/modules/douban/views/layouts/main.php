<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
		
		<title>手机上的豆瓣读书</title>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
		<link href="/static/douban/app.css" rel="stylesheet">
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
	</head>
	
	<body>
		<div data-role="page">
			<div data-role="header" data-position="fixed">
				<a href="/douban/default/index" class="ui-btn-right" data-transition="none">首页</a>
				<h3 id="page_title"><?php echo $this->title;?></h3>
			</div>
			<div data-role="content">
				<?php echo $content;?>
			</div>
		</div>
	</body>
</html>