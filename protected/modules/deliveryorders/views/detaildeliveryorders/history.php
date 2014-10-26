<?php
/* @var $this DetaildeliveryordersController */
/* @var $model Detaildeliveryorders */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/deliveryorders/detaildeliveryorders/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detaildeliveryorders', 'url'=>array('index')),
	//array('label'=>'Create Detaildeliveryorders', 'url'=>array('create')),
);

?>

<h1>Detil Pengiriman Barang</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detaildeliveryorders')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detaildeliveryorders-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		//'iddetail',
		//'id',
		'vouchername',
		'vouchervalue',
		'userlog',
		'datetimelog',
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
