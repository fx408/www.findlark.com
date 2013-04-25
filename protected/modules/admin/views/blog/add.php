<form action="" method="post">
	标题:<br>
	<input type="text" name="Form[title]" class="name"><br>
	简介:<br>
	<textarea name="Form[summary]" class="summary"></textarea>
	
	<br>
	内容:<textarea name="Form[content]" id="content"></textarea>
	
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
			resizeType : 1,
			height: 400,
			allowPreviewEmoticons : false,
			allowImageUpload : false,
			items : [
				'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
				'insertunorderedlist', '|', 'emoticons', 'image', 'link']
		});
	});
	
	$(function() {
		$("#submit").click(function() {
			editor.sync();
		});
	});
</script>