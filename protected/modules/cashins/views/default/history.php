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

<h1>Pencatatan Kas Masukl</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('cashins')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cashins-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'idcash',
			'value'=>"lookup::CashboxNameFromID(\$data['idcash'])",
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
			'updateButtonUrl'=>"Action::decodeRestoreHistoryCashInUrl(\$data)",
		),
	),
)); ?>
