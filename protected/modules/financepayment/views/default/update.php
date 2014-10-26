<?php
/* @var $this FinancepaymentsController */
/* @var $model Financepayments */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Pembayaran Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>