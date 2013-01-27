<link rel="stylesheet" type="text/css" href="/static/js/fancybox/jquery.fancybox-1.3.4.css">
<script type="text/javascript" src="/static/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>


<form action="/admin/image/add" method="post">
	标题:<input type="text" name="Form[title]">
	链接:<input type="text" name="Form[src]">
	panoramio:<input type="text" name="Form[panoramio_id]">
	
	<input type="submit" value="添加">
</form>
<div>
	<form action="/admin/image/index">
		标题:<input type="text" name="title" value="<?php echo $data['filter']['title'];?>">
		链接:<input type="text" name="src" value="<?php echo $data['filter']['src'];?>">
		panoramio:<input type="text" name="panoramio_id" value="<?php echo $data['filter']['panoramio_id'];?>">
		
		<input type="submit" value="查询">
		<a href="/admin/image/add">上传</a>
	</form>
</div>
<table>
	<thead>
		<tr>
			<th>标题</th>
			<th>panoramio</th>
			<th width="60%">链接</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data['list'] as $item) { ?>
		<tr>
			<td><?php echo $item->title;?></td>
			<td><a href="http://www.panoramio.com/photo/<?php echo $item->panoramio_id;?>" target="_blank"><?php echo $item->panoramio_id;?></a></td>
			<td><a href="<?php echo $item->src;?>" rel="image"><?php echo $item->src;?></a></td>
			<td>
				<a href="/admin/image/modify/id/<?php echo $item->id;?>">编辑</a>
				<a href="/admin/image/del/id/<?php echo $item->id;?>">删除</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<script type="text/javascript">
	$("a[rel=image]").fancybox({
		cyclic:true,
		speedOut: 10,
		transitionOut:'none'
	});
</script>
<?php
$this->renderPartial('/_page', array('data'=>$data['pager'], 'url'=>'/admin/image/index'));
?>