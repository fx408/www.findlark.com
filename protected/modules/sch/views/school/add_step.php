<div>
	<a href="javascript:;">创建学校</a> -> 
	<a href="javascript:;">添加校区</a> -> 
	<a href="javascript:;">上传图片</a> -> 
	<a href="javascript:;">添加记事</a>
</div>

<script type="text/javascript">
	function postMsg(error, msg, callback) {
		var color = error == 0 ? "green" : "red";
		
		$("#postInfo").css({color:color}).html(msg);
		
		if(error == 0 && callback) {
			callback();
		}
	}
	
	function submitForm(formId) {
		var $form = formId ? $("#"+formId) : $("#postForm");
		
		$form.find("input[type=button]").attr("disabled", "disabled");
		postMsg(0, "正则提交...");
		
		$.ajax({
			url: $form.attr("action"),
			data: $form.serialize(),
			type: "post",
			dataType: "json",
			success: function(data) {
				if(data.error == 0) {
					postMsg(0, "添加成功!", function() {
						document.location.href = data.params.url;
					});
				} else {
					postMsg(1, data.msg);
				}
				
				$form.find("input[type=submit]").removeAttr("disabled");
			},
			error: function() {
				$(this).find("input[type=submit]").removeAttr("disabled");
				postMsg(1, "提交失败!");
			}
		});
		
		return false;
	}
</script>