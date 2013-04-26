<div class="novel-show">
	<div class="novel-show-title">
		<a href="/blog/<?php echo $content->id;?>"><?php echo $content->title;?></a>
	</div>
	<div class="novel-show-content"><?php echo $content->content;?></div>
	<div class="novel-more">
		<div class="novel-prev">
			<?php
				if(!empty($prev)) printf('<a href="/blog/%d">上一篇：%s</a>', $prev->id, $prev->title);
				else echo '上一篇：无';
			?>
		</div>
		<div class="novel-next">
			<?php
				if(!empty($next)) printf('<a href="/blog/%d">下一篇：%s</a>', $next->id, $next->title);
				else echo '下一篇：无';
			?>
		</div>
		<div class="clear"></div>
	</div>
</div>


<link type="text/css" rel="stylesheet" href="/static/syntaxHighlighter/shStyle.css">
<script type="text/javascript" src="/static/syntaxHighlighter/syntaxHighlighter.js"></script>

<style>
	div.syntaxhighlighter table{border:1px solid #ccc; border-radius: 3px;}
</style>
<script type="text/javascript">
	//var list = {'php':1, 'js':1, 'html':1, 'css':1, 'sql':1, 'c':1};
	
	$(function() {
		$("pre.prettyprint").each(function() {
			var c = $(this).attr("class");
			c = c.replace(/^.*?\-(\w+)(\s|$)/, "$1");
			
			$(this).attr("class", "brush: "+c+";");
		});
		
		SyntaxHighlighter.all();
	});
</script>