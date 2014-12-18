<?php
/* @var $this CashoutsController */
/* @var $model Cashouts */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Cashouts', 'url'=>array('index')),
	array('label'=>'Create Cashouts', 'url'=>array('create')),
	array('label'=>'View Cashouts', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cashouts', 'url'=>array('admin')),*/
);
?>

<h1>Pencatatan Kas Keluar</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>