<div class="note">
	<div class="user_avatar">
		<img src="<?php echo $data->author_user->avatar;?>" uid="<?php echo $data->author_user->uid;?>" class="img-rounded">
	</div>
	<div class="note-info">
		<div>
			<a href="http://book.douban.com/people/<?php echo $data->author_user->uid;?>/" target="_blank"><?php echo $data->author_user->name;?></a>
		</div>
		<div>
			<small class="muted"><?php echo $data->time;?></small>
		</div>
	</div>
	<div class="clear"></div>
	<pre><?php echo $data->content;?></pre>
</div>
