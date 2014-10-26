<?php
/* @var $this DetailinputinventorytakingsController */
/* @var $model Detailinputinventorytakings */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view', 'id'=>$model->id),
      'Lihat Detil',
);

$this->menu=array(
	//array('label'=>'List Detailinputinventorytakings', 'url'=>array('index')),
	//array('label'=>'Create Detailinputinventorytakings', 'url'=>array('create')),
	array('label'=>'Ubah Detil', 'url'=>array('update', 'iddetail'=>$model->iddetail)),
	array('label'=>'Hapus Detil', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','iddetail'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Manage Detailinputinventorytakings', 'url'=>array('admin')),
   array('label'=>'Sejarah', 'url'=>array('history','iddetail'=>$model->iddetail)),
);
?>

<h1>Input Stok Opname</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'iditem',
			'value'=>lookup::ItemNameFromItemID($model['iditem']),
		),
		'qty',
		array(
			'name'=>'idwarehouse',
			'value'=>lookup::WarehouseNameFromWarehouseID($model['idwarehouse']),
		),
	),
)); ?>
