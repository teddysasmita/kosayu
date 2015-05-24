<?php
/* @var $this StockadjustmentsController */
/* @var $model Stockadjustments */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Penyesuaian Stok</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('stockadjustments')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stockadjustments-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'regnum',
		'idatetime',
		'itembatch',
		'oldamount',
		'newamount',
		array(
			'class'=>'CButtonColumn',
			'buttons'=> array(
			'view'=>array(
				'visible'=>'false',
			),
			'delete'=>array(
				'visible'=>'false',
			),
		),
		'updateButtonUrl'=>"Action::decodeRestoreHistoryStockAdjustmentUrl(\$data)",
		),
	),
)); ?>
