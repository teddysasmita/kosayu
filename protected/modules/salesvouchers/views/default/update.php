<?php
/* @var $this SalesvouchersController */
/* @var $model Salesvouchers */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Salesvouchers', 'url'=>array('index')),
	array('label'=>'Create Salesvouchers', 'url'=>array('create')),
	array('label'=>'View Salesvouchers', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Salesvouchers', 'url'=>array('admin')),*/
);
?>

<h1>Voucher / Kupon</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>