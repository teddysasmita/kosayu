<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Aktivitas Guide</title>
<style>
	@page { size: <?php echo $model->paperwidth; ?>mm <?php echo $model->paperheight; ?>mm;
		margin-bottom: <?php echo $model->paperbotm; ?>mm;
		margin-top: <?php echo $model->paperbotm; ?>mm; 
		margin-right: <?php echo $model->papersidem; ?>mm;
		margin-left: <?php echo $model->papersidem+10; ?>mm;
		page-break-before: 5mm;
	}
	
	.guidetable { 
		/*margin-left: <?php echo $model->paperwidth - ( 3 * $model->papersidem) - $model->labelwidth - (2 * $model->labelsidem); ?>mm;*/ 	
		border: none;
	}
	
	.guidetablerow { 
		height: <?php echo $model->labelheight; ?>mm;
		margin: <?php echo $model->labelbotm; ?>mm; 
	}
	.guidetablecol { 
		width: <?php echo $model->labelwidth; ?>mm;
		margin: <?php echo $model->labelsidem; ?>mm;
		 
	}
	.guidetablecell {  
		border: solid thin;
		-webkit-print-color-adjust: exact;
	 }
	 .cardcontent {
	 	background-color: yellow;
	 	height: <?php echo $model->labelheight-4; ?>mm;
	 	width: <?php echo $model->labelwidth-4; ?>mm;
	 	margin-top: <?php echo $model->labelbotm; ?>mm;
	 	margin-bottom: <?php echo $model->labelbotm; ?>mm;
	 	margin-right: <?php echo $model->labelsidem; ?>mm;
	 	margin-left: <?php echo $model->labelsidem; ?>mm;
	 	float: left;
	 	padding: 2mm;
	 	border: solid thin;
	 	page-break-inside: avoid;
	 }
	 .emptycontent {
	 	height: <?php echo $model->labelheight-2; ?>mm;
	 	width: <?php echo $model->labelwidth-2; ?>mm;
	 }
	 
	 .infofield {
	 	margin: 10mm; width: 25mm;
	 	font-family: monospace;
	 	font-size: 10pt;
	 }
	 .infovalue {
	 	margin: 10mm; width: 75mm;
	 	font-family: serif;
	 	font-size: 11pt;
	 	background-color: yellow;
	 	font-weight: bold;
	 }
	 .infocolon {
	 	background-color: yellow;
	 }
	 .cardtitle {
	 	text-align: center;
	 	height: 10mm;
	 }
	 .barcodeinfo {
	 	text-align: center;
	 	/*padding-top: 4mm;*/
	 }
	 .addressinfo {
	 	font-family: sans-serif;
	 	font-size: 8pt;
	 	text-align: center;
	 }
	 .cardtable {
	 	border-collapse: collapse;
	 	background-color: yellow;
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