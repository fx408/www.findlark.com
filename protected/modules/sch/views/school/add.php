<div>
	<?php $this->renderPartial('add_step');?>
	
	<form action="/sch/school/add" method="post" id="postForm">
		<div><h3>学校信息</h3></div>
		<table>
			<tr>
				<td>学校名称：</td>
				<td><input type="text" name="Form[name]" value=""></td>
				<td></td>
			</tr>
			
			<tr>
				<td>学校类型：</td>
				<td>
					<?php
					echo CHtml::dropDownList('Form[type]', '-1', SchoolType::model()->AList);
					?>
				</td>
				<td></td>
			</tr>
			
			<tr>
				<td>学校描述：</td>
				<td><textarea name="Form[desc]"></textarea></td>
				<td></td>
			</tr>
			
			<tr>
				<td>相关链接：</td>
				<td>
					<div class="clone_div">
						标题：<input type="text" name="Link[title][]">
						链接：http://<input type="text" name="Link[url][]">
					</div>
					<a href="javascript:;" id="add_link">增加</a>
				</td>
				<td></td>
			</tr>
			
			<tr>
				<td></td>
				<td>
					<input type="button" name="submit" value="提交"> 
					<span id="postInfo"></span>
				</td>
				<td></td>
			</tr>
		</table>
	</form>
</div>

<script type="text/javascript">
$(function() {
	$("#add_link").click(function() {
		var div = $("div.clone_div").clone();
		div.find("input[type=text]").val("");
		
		if(div.find("a.del").length == 0) div.append('<a href="javascript:;" class="del">删除</a>');
		
		$("div.clone_div:last").after(div);
	});
	
	$("td").on("click", "a.del", function() {
		$(this).parent("div").slideUp(100, function() {
			$(this).remove();
		});
	});
	
	$("#postForm input[name=submit]").click(function() {
		submitForm();
		return false;
	});
});
</script>