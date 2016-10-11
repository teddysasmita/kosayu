<?php
/* @var $this GuidesController */
/* @var $model Guides */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view', 'id'=>$model->id),
	'Pembayaran'
);

$this->menu=array(
	//array('label'=>'Daftar Pelanggan', 'url'=>array('index')),
	array('label'=>'Cetak', 'url'=>array('viewPayment', 'id'=>$model->id, 'startdate'=>$startdate, 
		'enddate'=>$enddate, 'print'=>'1'), 'linkOptions'=>['target'=>'_blank']),
);
?>

<h1>Pemandu / Guide</h1>

<div class='form'>

<?php 
	echo CHtml::beginForm(Yii::app()->createUrl('guides/default/viewPayment'), 'get');
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
				'dateFormat'=>'yy/mm/dd',
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
				'dateFormat'=>'yy/mm/dd',
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
					'header'=>'Nomor Urut',
					'name'=>'regnum',
				],
				[
					'header'=>'Tanggal',
					'name'=>'idatetime',		
				],
				[
					'header'=>'Total Komisi',
					'name'=>'commission',
					'type'=>'number',
				],
				[
					'header'=>'Titip',
					'name'=>'deposit',
					'type'=>'number',
				],
				[
					'header'=>'Jumlah',
					'name'=>'amount',
					'type'=>'number',
				],
			],			
 		]);
?>
</div>

<?php 
	$total=array();
	$total[0]['commission'] = 0;
	$total[0]['amount'] = 0;
	$total[0]['deposit'] = 0;
	foreach($data as $dt) {
		$total[0]['commission'] += $dt['commission'];
		$total[0]['amount'] += $dt['amount'];
	};
	$total[0]['deposit'] = end($data)['deposit'];
	$provider2 = new CArrayDataProvider($total);
	$this->widget('zii.widgets.grid.CGridView',[
			'dataProvider'=>$provider2,
			'columns'=>[
				[
					'header'=>'Total Komisi',
					'name'=>'commission',
					'type'=>'number',
				],
				[
					'header'=>'Dibayarkan',
					'name'=>'amount',
					'type'=>'number',
				],
				[
					'header'=>'Titipan',
					'name'=>'deposit',
					'type'=>'number',
				],
			],
	]);
?>

