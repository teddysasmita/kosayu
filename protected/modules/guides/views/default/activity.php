<?php
/* @var $this GuidesController */
/* @var $model Guides */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view', ['id'=>$model->id]),
	'Aktifitas'
);

$this->menu=array(
	//array('label'=>'Daftar Pelanggan', 'url'=>array('index')),
	array('label'=>'Cetak', 'url'=>array('printActivity')),
);
?>

<h1>Pemandu / Guide</h1>

<div class='form'>

<?php 
	echo CHtml::beginForm(Yii::app()->createUrl('guides/default/viewActivity'), 'get');
	echo CHtml::hiddenField('id',$model->id);
?>

<div class="row">
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
	?>
</div>

<div class="row">
	<?php 
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

<div class='row'>
	<?php 
		echo CHtml::submitButton('Cari');
	?>
</div>
		
<?php echo CHtml::endForm() ?>

<?php 

$provider1 = new CArrayDataProvider($data);

$this->widget('zii.widgets.grid.CGridView',[ 
		'dataProvider'=>$provider1,
			'columns'=>[
				[
					'header'=>'Nomor Stiker',
					'name'=>'stickernum',
				],
				[
					'header'=>'Tanggal',
					'name'=>'stickerdata',		
				],
			],			
 		]);
?>

</div>

