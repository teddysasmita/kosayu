<?php
/* @var $this SalesvouchersController */
/* @var $model Salesvouchers */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Voucher / Kupon</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>