<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Items', 'url'=>array('index')),
	array('label'=>'Create Items', 'url'=>array('create')),
	array('label'=>'View Items', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Items', 'url'=>array('admin')),*/
);
?>

<h1>Item Penjualan</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>