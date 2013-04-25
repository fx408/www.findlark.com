<form action="" method="post">
	标题:<br>
	<input type="text" name="Form[title]" class="name" value="<?php echo $data->title;?>"><br>
	简介:<br>
	<textarea name="Form[summary]" class="summary"><?php echo $data->summary;?></textarea>
	
	<br>
	内容:<textarea name="Form[content]" id="content"><?php echo $data->content;?></textarea>
	
	<input type="submit" value="提交" id="submit">
</form>


<div>
	<a href="/admin/blog/index">列表</a>
</div>

<link rel="stylesheet" href="/static/keditor/themes/default/default.css">
<script charset="utf-8" src="/static/keditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/static/keditor/lang/zh_CN.js"></script>

<script type="text/javascript">
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('#content', {
			allowFileManager : true,
			height: 400,
			width: 800
		});
	});
	
	$(function() {
		$("#submit").click(function() {
			editor.sync();
		});
	});
</script>