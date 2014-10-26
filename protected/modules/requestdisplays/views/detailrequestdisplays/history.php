<?php
/* @var $this DetailrequestdisplaysController */
/* @var $model Detailrequestdisplays */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/requestdisplays/detailrequestdisplays/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailrequestdisplays', 'url'=>array('index')),
	//array('label'=>'Create Detailrequestdisplays', 'url'=>array('create')),
);

?>

<h1>Detil Permintaan Barang Display</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailrequestdisplays')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailrequestdisplays-grid',
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
