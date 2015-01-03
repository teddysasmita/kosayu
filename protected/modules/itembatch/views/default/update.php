<?php
/* @var $this ItembatchController */
/* @var $model Itembatch */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Itembatch', 'url'=>array('index')),
	array('label'=>'Create Itembatch', 'url'=>array('create')),
	array('label'=>'View Itembatch', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Itembatch', 'url'=>array('admin')),*/
);
?>

<h1>Kode Batch Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>