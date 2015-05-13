<?php
/* @var $this PurchasesController */
/* @var $model Purchases */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Pembelian dari Pemasok</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('purchases')->where('id=:id',array(':id'=>$model->id))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'purchases-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'regnum',
		'idatetime',
		'idorder',
		'idsupplier',
		'total',
		/*
		'discount',
		'status',
		'remark',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryCustomerUrl(\$data)",
		),
	),
)); ?>
