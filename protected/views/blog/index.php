<div class="blog">
	<div class="blog-list" id="blog-list">
		<?php
		foreach($list as $item) {
		?>
		<div class="novel" style="display:none" novelid="<?php echo $item->id;?>">
			<div class="novel-title"><a href="/blog/<?php echo $item->id;?>"><?php echo $item->title;?></a></div>
			<div class="novel-summary"><?php echo $item->summary;?>...</div>
			<div class="novel-operation"></div>
		</div>
		<?php
		}
		?>
	</div>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->params->staticUrl;?>/js/imageLoader.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params->staticUrl;?>/js/	jquery.masonry.min.js"></script>

<script type="text/javascript">

	$("#blog-list").masonry({
		isAnimated: false,
		singleMode: true,
		isFitWidth: true,
		itemSelector: '.novel'
	});
	
	setTimeout(function() {
		$("#blog-list .novel:hidden").show();
	}, 100);
	
	$(window).scroll(function() {
		var pool = $(document).height() - $(this).scrollTop() - $(this).height();
		
		if(pool < 200) {
			novel.loadData();
		}
	});
	
	var novel = {
		page: 3,
		pageSize: 10,
		isMax: false,
		
		loadData: function() {
			if(this.isMax === true) return false;
			
			var _this = this;
			
			$.ajax({
				url:'/blog/ajaxList/page/'+_this.page,
				type:'get',
				data:'',
				dataType:'json',
				success: function(data) {
					if(data == '') {
						_this.isMax = true;
						return false;
					}
					_this.page++;
					for(var k in data) {
						_this.mkHtml(data[k]);
					}
					setTimeout(function() {
						$("#blog-list .novel:hidden").show();
					}, 200);
				},
				error: function() {
				}
			});
		},
		
		mkHtml: function(data) {
			var html = '<div class="novel" style="display:none;" novelid="'+data.id+'">'
			+ '<div class="novel-title"><a href="/blog/'+data.id+'">'+data.title+'</a></div>'
			+ '<div class="novel-summary">'+data.summary+'...</div>'
			+ '<div class="novel-operation"></div>'
			+ '</div>';
			
			$boxes = $(html);
			$("#blog-list").append($boxes).masonry('appended', $boxes);
		}
	};
</script>