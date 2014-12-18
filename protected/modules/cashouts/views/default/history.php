<?php
/* @var $this CashOutsController */
/* @var $model CashOuts */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Penentuan Harga Jual</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('cashouts')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cashouts-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'idexpense',
			'value'=>"lookup::ExpenseNameFromID(\$data['idexpense'])",
		),
		'idacctcredit',
		'amount',
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
			'updateButtonUrl'=>"Action::decodeRestoreHistoryCashOutUrl(\$data)",
		),
	),
)); ?>
