<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Aktivitas Guide</title>
<style>
	@page { size: 215mm 280mm;
		margin: 10mm;
		page-break-before: 5mm;
	} 
</style>
</head>
<body>


</div>

<div id="toolbar">
<?php 
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCoreScript('jquery.ui');

	
?>
</div>
<div id="datalist">
<?php 
	$dp = new CArrayDataProvider($data);
	
	$this->widget('zii.widgets.grid.CGridView', 
		['dataProvider'=>$dp]);
?>
</div>

<?php echo Chtml::endform();?>
</body>
</html>