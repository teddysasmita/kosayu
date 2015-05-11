<?php
/* @var $this EmployeesController */
/* @var $model Employees */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Employees', 'url'=>array('index')),
	array('label'=>'Create Employees', 'url'=>array('create')),
	array('label'=>'View Employees', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Employees', 'url'=>array('admin')),*/
);
?>

<h1>Karyawan</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>