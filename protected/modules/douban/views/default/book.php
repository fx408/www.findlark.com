<div class="book-detail">
	<ul data-role="listview" data-divider-theme="b" data-inset="true">
		<li data-role="list-divider" role="heading">《<?php echo $data->title;?>》</li>
		<li data-theme="c">
			作者：<?php echo $data->author;?>
		</li>
		<li data-theme="c">
			得分：<span class="text-red"><?php echo $data->score;?></span>
			<small class="muted">(共<?php echo $data->numRaters;?>人评分)</small>
		</li>
		<li data-theme="c">
			标签：<?php echo $data->tags;?>
		</li>
		<li data-theme="c">
			<a href="/douban/default/noteList/bookid/<?php echo $data->bookid;?>" data-transition="none">读书笔记</a>
		</li>
	</ul>
	<?php
		$reading = BookHelper::getBookReading($data->bookid);
		if($reading) {
	?>
	<ul data-role="listview" data-divider-theme="b" data-inset="true">
		<li data-role="list-divider" role="heading">章节试读</li>
	<?php
		foreach($reading as $n => $id) {
			printf('<li data-theme="c"><a href="/douban/default/reading/bookid/%d/id/%d" data-transition="none">试读章节 (%d)</a></li>',
			$data->bookid, $id, $n+1);
		}
		?>
	<?php } ?>
	</ul>
	
	<div data-role="collapsible" data-collapsed="false" data-theme="b">
		<h3>作者介绍</h3>
		<pre><?php echo CHtml::encode($data->author_intro);?></pre>
	</div>
	
	<div data-role="collapsible" data-collapsed="false" data-theme="b">
		<h3>目录一览</h3>
		<pre><?php echo  CHtml::encode($data->catalog);?></pre>
	</div>
	
	<div data-role="collapsible" data-collapsed="false" data-theme="b">
		<h3>内容提要</h3>
		<pre><?php echo  CHtml::encode($data->summary);?></pre>
	</div>
</div>