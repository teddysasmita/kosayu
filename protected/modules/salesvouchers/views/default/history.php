<?php
/* @var $this SalesvouchersController */
/* @var $model Salesvouchers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Voucher / Kupon</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('salesvouchers')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'salesvouchers-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'regnum',
		'amount',
		'idatetime',
		/*
		'objects',
		'model',
		'attribute',
		'picture',
		'rowdeleted',
		'userlog',
		'datetimelog',
		*/
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryItemUrl(\$data)",
		),
	),
)); ?>
