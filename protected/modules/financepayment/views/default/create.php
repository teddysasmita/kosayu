<?php
/* @var $this FinancepaymentsController */
/* @var $model Financepayments */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Pembayaran Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>