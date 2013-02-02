<table>
	<thead>
		<tr>
			<th>标题</th>
			<th>路径</th>
			<th>快照</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data['list'] as $item) { ?>
		<tr>
			<td><a href="/extends/<?php echo $item->path;?>" target="_blank"><?php echo $item->title;?></a></td>
			<td><?php echo $item->path;?></td>
			<td><img src="<?php echo $item->thumb;?>" width="180"></td>
			<td>
				<a href="/admin/tool/modify/id/<?php echo $item->id;?>" class="modify">编辑</a>
				<a href="/admin/tool/del/id/<?php echo $item->id;?>" class="del">删除</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php
$this->renderPartial('/_page', array('data'=>$data['pager'], 'url'=>'/admin/image/index'));
?>

<link rel="stylesheet" type="text/css" href="/static/js/fancybox/jquery.fancybox-1.3.4.css">
<script type="text/javascript" src="/static/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript">
	$("a.modify").fancybox({
		cyclic:true,
		speedOut: 10,
		transitionOut:'none'
	});
</script>