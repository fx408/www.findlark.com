<h3>图片上传</h3>

<div id="upload_form_list">
	<form action="/admin/image/upload" method="post" target="image_upload" enctype="multipart/form-data">
		标题：<input type="text" name="title">
		panoramio:<input type="text" name="panoramio_id">
		
		文件：<input type="file" name="image">
		
		<span class="info"></span>
	</form>
</div>


<div>
	<button id="start_upload">上传</button> 
	<button id="add_form">增加</button> 
	<a href="/admin/image/index">列表</a>  
</div>
<iframe name="image_upload" id="image_upload" style="border:1px solid #777; width:100%;"></iframe>

<script type="text/javascript">
	var i = 0, l = 0;
	
	$(function() {
		var $form = $("#upload_form_list form:eq(0)").clone();
		$("#start_upload").click(function() {
			i = 0, l = $("#upload_form_list form").length;
			
			$("#upload_form_list .info").empty();
			submitForm();
			return false;
		});
		
		$("#add_form").click(function() {
			$("#upload_form_list").append('<br>');
			$("#upload_form_list").append($form.clone());
			return false;
		}).trigger("click").trigger("click").trigger("click");
		
		$("#image_upload").load(function() {
			var result = $(document.getElementById('image_upload').contentWindow.document).find("body").html();
			result = JSON.parse(result);

			if(result.error != 0) result.error = 1;
			setMessage(result.msg, result.error);
			
			setTimeout(function() {
				submitForm();
			}, 200);
		});
	});
	
	function submitForm() {
		if(i >= l) return false;
		$form = $("#upload_form_list form:eq("+i+")");
		var file = $form.find("input[name=image]").val();
		
		i++;
		if(file.length == 0) {
			submitForm();
		} else {
			setMessage('上传中...', 0);
			$form.submit();
		}
	}
	
	var c = ['green', 'red'];
	function setMessage(message, colorId, n) {
		if(typeof n === 'undefined') n = i - 1;
		
		$("#upload_form_list form").eq(n).find(".info").html(message).css("color", c[colorId]);
	}
</script>