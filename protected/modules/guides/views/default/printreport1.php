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
<?php echo CHtml::beginForm(Yii::app()->createUrl('printActivity')); ?>
<div id="toolbar">
<?php
	echo CHtml::label('Tanggal Awal', 'startdate');
	$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'startdate',
			// additional javascript options for the date picker plugin
			'options'=>array(
				'showAnim'=>'fold',
			),
			'htmlOptions'=>array(
					'id'=>'startdate',
					'style'=>'height:20px;'
			),
			'value'=>$startdate,
	));
	
	echo CHtml::label('Tanggal Akhir', 'startdate');
	$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'enddate',
			// additional javascript options for the date picker plugin
			'options'=>array(
					'showAnim'=>'fold',
			),
			'htmlOptions'=>array(
					'id'=>'enddate',
					'style'=>'height:20px;'
			),
			'value'=>$enddate,
	));
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