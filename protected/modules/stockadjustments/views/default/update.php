<?php
/* @var $this StockadjustmentsController */
/* @var $model Stockadjustments */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Stockadjustments', 'url'=>array('index')),
	array('label'=>'Create Stockadjustments', 'url'=>array('create')),
	array('label'=>'View Stockadjustments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Stockadjustments', 'url'=>array('admin')),*/
);
?>

<h1>Penyesuaian Stok</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>