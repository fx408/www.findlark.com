<div>
	<?php $this->renderPartial('add_step', array('now'=>2));?>
	
	<div><h3>学校照片</h3></div>
	<table id="pic_table">
		<thead>
			<th>照片标题</th>
			<th>所属校区</th>
			<th>选择照片</th>
			<th>信息</th>
		</thead>
		
		<tbody>
			<tr>
				<td><input type="text" name="form_title" value=""></td>
				<td>
					<?php
					echo CHtml::dropDownList('Form[type]', '-1', SchoolZone::model()->zoneList);
					?>
				</td>
				<td>
					<form action="/sch/school/addPic" method="post" target="upload_iframe">
						<input type="hidden" name="Form[title]" value="">
						<input type="hidden" name="Form[zone_id]" value="">
						<input type="file" name="Form[file]" value="">
					</form>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
	
	<div>
		<input type="button" id="add_pic" value="增加">
		<input type="button" id="submit_pic" value="提交">
		
		<a href="/sch/school/addNote">下一步</a>
	</div>
</div>

<iframe name="upload_iframe" id="upload_iframe" style="-display:none"></iframe>

<script type="text/javascript">
	
	
	
$(function() {
	$("#add_pic").click(function() {
		var tr = $("#pic_table tbody tr:eq(0)").clone();
		tr.find("input").val("");
		
		tr.find("td:last").html('<a href="javascript:;" class="del">删除</a>');
		
		$("#pic_table tbody").append(tr);
	});
	
	$("#pic_table tbody").on("click", "a.del", function() {
		$(this).parents("tr:eq(0)").remove();
		return false;
	});
	
	$("#submit_pic").click(function() {
		l = $("form").length, i = 0;
		uploadPic();
	});
	
	// 上传之后
	$("#upload_iframe").load(function() {
		var r = $(document.getElementById('upload_iframe').contentWindow.document).find("body").html();
		r = $.parseJSON(r);
		
		var c = r.error == 0 ? 'green' : "red";
		
		$("form").eq(i-1).parent("td").next().css("color", c).html(r.msg);
		setTimeout(function() {
			uploadPic();
		}, 100);
	});
	
});
var i = 0, l = 0;
// 逐个 提交表单
function uploadPic() {
	if(i >= l) {
		return false;
	}
	var formObj = $("form:eq("+i+")"),
		picTitle = formObj.parents("tr:eq(0)").find("input[name=form_title]").val(),
		zone = formObj.parents("tr:eq(0)").find("input[name=form_zone]").val();
	
	formObj.find("input[name='Form[title]']").val(picTitle);
	formObj.find("input[name='Form[zone_id]']").val(zone);
	
	formObj.parent("td").next().css("color", "green").html("上传中...");
	
	formObj.submit();
	i++;
}
</script>