<?php
/* @var $this DetailsalesretursController */
/* @var $model Detailsalesreturs */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/salesreturs/detailsalesreturs/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailsalesreturs', 'url'=>array('index')),
	//array('label'=>'Create Detailsalesreturs', 'url'=>array('create')),
);

?>

<h1>Detil Retur Penjualan</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailsalesreturs')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailsalesreturs-grid',
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
