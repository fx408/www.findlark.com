<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>

<div>
	<a href="<?php echo Yii::app()->params->baseUrl;?>">首页</a> | 
	<a href="javascript:history.go(-1)">返回</a>
</div>