<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
		<title>FindLark.<?php echo CHtml::encode($this->pageTitle);?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="keywords" content="<?php echo CHtml::encode($this->pageKeywords);?>">
		<meta name="description" content="<?php echo CHtml::encode($this->pageDescription);?>">
		<meta name="application-name" content="findlark">
		<meta name="msapplication-starturl" content="http://www.findlark.com/">
		
		<link rel="stylesheet" type="text/css" href="/static/css/public.css">
		<link rel="stylesheet" type="text/css" href="/static/css/<?php echo $this->id;?>.css">
		
		<script type="text/javascript" src="/static/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="/static/js/jquery.cookie.js"></script>
	</head>
	<body>
		<?php echo $content;?>
		
		<div style="display:none">
			<a href="<?php echo Yii::app()->params->baseUrl;?>" title="FindLark">FindLark</a>
			<a href="<?php echo Yii::app()->params->baseUrl;?>/blog" title="博客">博客</a>
			<a href="<?php echo Yii::app()->params->baseUrl;?>/tool" title="tool">tool</a>
			
			<script type="text/javascript">
			var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
			document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F5ba2bf410b154f773b29948e2ddcb0f4' type='text/javascript'%3E%3C/script%3E"));
			</script>
		</div>
	</body>
</html>