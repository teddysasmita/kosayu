<?php
/* @var $this CashinsController */
/* @var $model Cashins */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Cashins', 'url'=>array('index')),
	array('label'=>'Create Cashins', 'url'=>array('create')),
	array('label'=>'View Cashins', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cashins', 'url'=>array('admin')),*/
);
?>

<h1>Pencatatan Kas Masuk</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>