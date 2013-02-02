<div class="extends_list">
	<?php foreach($data as $item) { ?>
		<div class="extend">
			<div class="extend-img"><a href="/extends/<?php echo $item->path;?>"><img src="<?php echo $item->thumb;?>"></a></div>
			<div class="extend-link"><a href="/extends/<?php echo $item->path;?>"><?php echo $item->title;?></a></div>
		</div>
		
	<?php } ?>
</div>

<script type="text/javascript" src="/static/js/jquery.masonry.min.js"></script>
<script type="text/javascript">
	$(function() {
		$(".extends_list").masonry({
			singleMode: true,
			isFitWidth: true,
			itemSelector: '.extend'
		});
	})
</script>