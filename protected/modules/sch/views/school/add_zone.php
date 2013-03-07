<div>
	<?php $this->renderPartial('add_step');?>
	
	<form action="/sch/school/addZone" method="post" id="postForm">
		<input type="hidden" name="Form[school_id]" value="0">
		
		<div><h3>校区信息</h3></div>
		<div class="clone_div">
			<table>
				<tr>
					<td>校区名称：</td>
					<td><input type="text" name="Form[name]" value=""></td>
					<td></td>
				</tr>
				
				<tr>
					<td>校区类型：</td>
					<td>
						<?php
						echo CHtml::dropDownList('Form[type]', '-1', SchoolType::model()->BList);
						?>
					</td>
					<td></td>
				</tr>
				
				<tr>
					<td>校区描述：</td>
					<td><textarea name="Form[desc]"></textarea></td>
					<td></td>
				</tr>
				
				<tr>
					<td>所在地区：</td>
					<td></td>
					<td></td>
				</tr>
				
				<tr>
					<td>详细地址：</td>
					<td><textarea name="Form[address]"></textarea></td>
					<td></td>
				</tr>
				
				<tr>
					<td>地图定位：</td>
					<td></td>
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
		</div>
	</form>
</div>

<script type="text/javascript">
	
$(function() {
	$("#postForm input[name=submit]").click(function() {
		submitForm();
		return false;
	});
	
	$("#postForm input[name=continue]").click(function() {
		$("#form_continue").val(1);
		submitForm();
		return false;
	});
});
	
</script>
