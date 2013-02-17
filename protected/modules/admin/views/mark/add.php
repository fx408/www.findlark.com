<h3>添加标记</h3>

<form action="/admin/mark/<?php echo isset($data) ? 'modify/id/'.$data->id : 'add';?>" method="post">
	作者:<input type="text" name="Form[author]" value="<?php if( isset($data) ) echo $data->author;?>">
	标题:<input type="text" name="Form[title]" value="<?php if( isset($data) ) echo $data->title;?>">
	显示:<input type="text" name="Form[display]" value="<?php echo isset($data) ? $data->display : 1;?>">
	
	<br>
	经度:<input type="text" name="Form[longitude]" value="<?php if( isset($data) ) echo $data->longitude;?>">
	纬度:<input type="text" name="Form[latitude]" value="<?php if( isset($data) ) echo $data->latitude;?>">
	
	
	<br>
	内容:<textarea name="Form[content]" style="width:473px; height:120px;"><?php if( isset($data) ) echo $data->content;?></textarea>
	<br>
	<input type="submit" value="提交">
</form>

<div>
	<a href="/admin/mark/index">列表</a>
</div>
