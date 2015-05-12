<?php
/* @var $this JobgroupsController */
/* @var $model Jobgroups */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Jobgroups', 'url'=>array('index')),
	array('label'=>'Create Jobgroups', 'url'=>array('create')),
	array('label'=>'View Jobgroups', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Jobgroups', 'url'=>array('admin')),*/
);
?>

<h1>Jabatan</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>