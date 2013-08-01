<style>
	textarea{width:400px; height:80px;}
	input{width:400px;}
	
</style>

<form action="/admin/book/index/">
<input type="text" name="bookid" value="">
<input type="submit" value="提交">
</form>

<form action="/admin/book/save/bookid/<?php echo $data->bookid;?>" method="post">
	<div>标题：<input type="text" name="book[title]" value="<?php echo $data->title;?>"></div>
	<div>作者：<input type="text" name="book[author]" value="<?php echo $data->author;?>"></div>
	<div>标签：<input type="text" name="book[tags]" value="<?php echo $data->tags;?>"></div>
	<div>评数：<input type="text" name="book[numRaters]" value="<?php echo $data->numRaters;?>"></div>
	<div>评分：<input type="text" name="book[score]" value="<?php echo $data->score;?>"></div>
	<div>简述：<input type="text" name="book[description]" value="<?php echo $data->description;?>"></div>
	<div>图片：<input type="text" name="book[img]" value="<?php echo $data->img;?>"></div>
	<div>描述：<textarea type="text" name="book[summary]"><?php echo $data->summary;?></textarea></div>
	<div>目录：<textarea type="text" name="book[catalog]"><?php echo $data->catalog;?></textarea></div>
	<div>作者：<textarea type="text" name="book[author_intro]"><?php echo $data->author_intro;?></textarea></div>
	
	<div>权重：<input type="text" name="weights" value="1"></div>
	<input type="submit" value="提交">
</form>