<?php
foreach($data as $note) {
	echo <<<EOF
<div class="note">
	<div class="user_avatar">
		<img src="{$note['author_user']['avatar']}" uid="{$note['author_user']['uid']}" class="img-rounded">
	</div>
 <div class="note-info">
	<div><a href="http://book.douban.com/people/{$note['author_user']['uid']}/" target="_blank">{$note['author_user']['name']}</a></div>
	<div>
		<a href="/douban/default/note/bookid/{$book->bookid}/noteid/{$note['id']}" class="note_detail" data-transition="none">第{$note['page_no']}页</a>
		<small class="muted">{$note['time']}</small>
	</div>
 </div>
	<div class="clear"></div>
	<pre>{$note['summary']}</pre>
</div>
EOF;
}
?>