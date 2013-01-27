<form action="/admin/tool/modify/id/<?php echo $data->id;?>" method="post">
	标题:<input type="text" name="title" value="<?php echo $data->title;?>"><br>
	路径:<input type="text" name="path" value="<?php echo $data->path;?>"><br>
	
	<input type="submit" value="提交">
</form>