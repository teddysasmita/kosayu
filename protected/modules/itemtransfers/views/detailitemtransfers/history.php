<?php
/* @var $this DetailitemtransfersController */
/* @var $model Detailitemtransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/itemtransfers/detailitemtransfers/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailitemtransfers', 'url'=>array('index')),
	//array('label'=>'Create Detailitemtransfers', 'url'=>array('create')),
);

?>

<h1>Detil Pemindahan Barang</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailitemtransfers')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailitemtransfers-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		//'iddetail',
		//'id',
		'iditem',
		'qty',
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
