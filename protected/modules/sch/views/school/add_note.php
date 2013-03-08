<div>
	<?php $this->renderPartial('add_step', array('now'=>3));?>
	
	<form action="/sch/school/addNote" method="post" id="postForm">
		<div><h3>校园记事</h3></div>
		<table>
			<tr>
				<td>记事标题：</td>
				<td><input type="text" name="Form[title]" value=""></td>
				<td></td>
			</tr>
			<tr>
				<td>发生时间：</td>
				<td><input type="text" name="Form[occurrence_time]" value="" id="form_occurrence_time" readonly="true"></td>
				<td></td>
			</tr>
			
			<tr>
				<td>记事内容：</td>
				<td><textarea name="Form[content]"></textarea></td>
				<td></td>
			</tr>
			
			<tr>
				<td></td>
				<td>
					<input type="hidden" name="Form[continue]" value="0" id="form_continue">
					<input type="button" name="submit" value="提交">
					<input type="button" name="continue" value="提交并继续添加"> 
					<span id="postInfo"></span>
				</td>
				<td></td>
			</tr>
		</table>
	</form>
</div>

<script type="text/javascript" src="/static/js/lhgcalendar/lhgcalendar.js"></script>
<script type="text/javascript">
$(function() {
	$("#form_occurrence_time").calendar({btnBar:true});
	
	
	$("#postForm input[name=submit]").click(function() {
		submitForm();
		return false;
	});
	
	$("#postForm input[name=continue]").click(function() {
		$("#form_continue").val(1);
		submitForm(null, function() {
			$("#postForm").find("input[type=text]").val("")
				.end().find("textarea").val("");
		});
		return false;
	});
});
</script>