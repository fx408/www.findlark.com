<div class="step_list">
	<ul>
		<?php
		$steps = array('创建学校 -&gt;', '添加校区 -&gt;', '上传图片 -&gt;', '添加记事');
		if(!isset($now)) $now = 0;
		
		foreach($steps as $k => $v) {
			printf('<li class="%s">%s</li>', 
				($now == $k ? 'current' : ($k < $now ? 'yes' : '')), $v);
		}
		
		?>
	</ul>
</div>
<div class="clear"></div>

<script type="text/javascript">
	function postMsg(error, msg, callback, data) {
		var color = error == 0 ? "green" : "red";
		
		$("#postInfo").css({color:color}).html(msg);
		
		if(error == 0 && callback) {
			callback(data);
		}
	}
	
	function submitForm(formId, callback) {
		var $form = formId ? $("#"+formId) : $("#postForm");
		
		$form.find("input[type=button]").attr("disabled", "disabled");
		postMsg(0, "正则提交...");
		
		if(!callback) {
			callback = function(data) {
				document.location.href = data.params.url;
			}
		}
		
		$.ajax({
			url: $form.attr("action"),
			data: $form.serialize(),
			type: "post",
			dataType: "json",
			success: function(data) {
				if(data.error == 0) {
					postMsg(0, "添加成功!", callback, data);
				} else {
					postMsg(1, data.msg);
				}
				
				$form.find("input[type=button]").removeAttr("disabled");
			},
			error: function() {
				$form.find("input[type=button]").removeAttr("disabled");
				postMsg(1, "提交失败!");
			}
		});
		
		return false;
	}
	
</script>