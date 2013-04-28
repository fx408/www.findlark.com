<div id="header">
	<div class="header-middle">
		<div id="header-logo"><a href="/">FindLark.</a></div>
		<div class="header-nav" id="header-nav">
			<ul>
				<li class="current"><a href="/home" name="home">Lark</a></li>
				<li><a href="/blog" name="blog">小记</a></li>
				<li><a href="http://hi.baidu.com/findlark" name="space">空间</a></li>
				<li><a href="/tool" name="3">扩展</a></li>
			</ul>
		</div>
		<div class="current-nav" id="current-nav"></div>
	</div>
	<div class="clear-header"></div>
</div>

<div class="header-bg" id="header-bg"></div>

<!--Main-//-->
<div class="main" id="main"><iframe src="" id="content" class="content" frameborder="no" border="0" scrolling="yes" allowTransparency="true"></iframe></div>

<script type="text/javascript" src="/static/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/static/js/index.js"></script>
<script type="text/javascript">
	$(function () {
		iObj.init();
	});
</script>
<div style="height:0px; width:0px; overflow:hidden; line-height:0px; padding:0px; margin:0px;">
	<?php
	$data = LarkNovel::model()->newArticles();
	foreach($data as $item) {
		printf('<a href="%s/blog/%d" title="%s">%s</a>', Yii::app()->params->baseUrl, $item->id, $item->title, $item->title);
	}
	?>
</div>