<div>
	<?php $this->renderPartial('add_step', array('now'=>1));?>
	
	<form action="/sch/school/addZone" method="post" id="postForm">
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
					<td id="city"></td>
					<td></td>
				</tr>
				
				<tr>
					<td>详细地址：</td>
					<td><textarea name="Form[address]"></textarea></td>
					<td></td>
				</tr>
				
				<tr>
					<td>地图定位：</td>
					<td>
						纬度：<input type="text" name="Form[latitude]" value="" id="form_latitude">
						经度：<input type="text" name="Form[longitude]" value="" id="form_longitude">
						<br>
						<a href="/sch/school/picker" id="position">定位</a>
					</td>
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

<link rel="stylesheet" type="text/css" href="/static/js/colorbox/colorbox.css">
<script type="text/javascript" src="/static/js/colorbox/jquery.colorbox.js"></script>

<script type="text/javascript">
function createCitysSelectList(parent_id, selectName) {
	$.get("/sch/default/citys", {parent_id:parent_id}, function(data) {
		if(!data) return;
		html = '';
		
		if(parent_id == 0) html = '<option value="-1">请选择</option>';
		
		for(var k in data) {
			html += '<option value="'+k+'">'+data[k]+'</option>';
		}
		
		if(html == '') return;

		if( $("#form_"+selectName).length == 0) {
			html = '<select name="From['+selectName+']" id="form_'+selectName+'">'+html+'</select>';
			$("#city").append(html);
		} else {
			$("#form_"+selectName).html(html);
		}
		
		if(parent_id != 0) $("#form_"+selectName).trigger("change");
	}, "json");
}

var position = {};
function positionCallback(p) {
	$("#form_latitude").val(p.ib.toFixed(4));
	$("#form_longitude").val(p.jb.toFixed(4));
	
	
}

function praseAddress(address) {
	
	console.log(address);
}

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
	
	$("#city").on("change", "#form_provinces, #form_city", function() {
		var val = $(this).val(), 
			_id = $(this).attr("id"), 
			selectName = "";
		
		if(val == -1) {
			$("#form_city, #form_county").remove();
			return;
		}
		
		if(_id == "form_provinces") {
			selectName = "city";
			$("#form_city, #form_county").remove();
		} else {
			$("#form_county").remove();
			selectName =  "county";
		}
		
		createCitysSelectList(val, selectName);
	});
	
	$("#position").colorbox({
		iframe:true,
		width:"80%",
		height:"80%",
		opacity: 0.5,
		close: '<button>确认选择</button>'
	});
	
	createCitysSelectList(0, 'provinces');
});
	
</script>
