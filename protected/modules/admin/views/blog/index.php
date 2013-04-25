<div>
	<form action="" method="get">
		ID:<input type="text" value="" name="id">
		标题:<input type="text" value="" name="title">
		<input type="submit" value="查询">
	</form>
	
	<a href="/admin/blog/add">添加</a>
</div>
<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>标题</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data['list'] as $item) { ?>
		<tr>
			<td><?php echo $item->id;?></td>
			<td><a href="/blog/<?php echo $item->id;?>" target="_blank"><?php echo $item->title;?></a></td>
			<td>
				<a href="/admin/blog/modify/id/<?php echo $item->id;?>" class="modify">编辑</a>
				<a href="/admin/blog/del/id/<?php echo $item->id;?>" class="del">删除</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php
$this->renderPartial('/_page', array('data'=>$data['pager'], 'url'=>'/admin/image/index'));
?>