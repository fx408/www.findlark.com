<form action="/admin/tool/modify/id/<?php echo $data->id;?>" method="post">
	标题:<input type="text" name="title" value="<?php echo $data->title;?>" class="ibox"><br>
	路径:<input type="text" name="path" value="<?php echo $data->path;?>" class="ibox"><br>
	
	快照:<input type="text" name="thumb" value="<?php echo $data->thumb;?>" class="ibox"><br>
	
	<input type="submit" value="提交">
</form>

<div>
	<img src="<?php echo $data->thumb;?>" style="max-width:100%; max-height:100%">
</div>